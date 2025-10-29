<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class BiometricService
{
    /**
     * Register biometric data (fingerprint/facial template)
     */
    public function registerBiometric(string $userId, string $biometricType, array $biometricData): array
    {
        try {
            // Hash the biometric template for security (one-way hash)
            $hashedTemplate = $this->hashBiometricTemplate($biometricData);
            
            // Store encrypted biometric data
            $encryptedData = encrypt(json_encode($biometricData));
            
            \DB::table('biometric_data')->insert([
                'user_id' => $userId,
                'biometric_type' => $biometricType, // 'fingerprint', 'facial', 'iris', 'voice'
                'template_hash' => $hashedTemplate,
                'encrypted_template' => $encryptedData,
                'device_info' => request()->userAgent(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            return [
                'success' => true,
                'message' => 'Biometric data registered successfully',
                'template_id' => $hashedTemplate
            ];
        } catch (\Exception $e) {
            Log::error('Biometric registration failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to register biometric data: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Verify biometric against stored templates
     */
    public function verifyBiometric(string $userId, string $biometricType, array $biometricData): array
    {
        try {
            $hashedTemplate = $this->hashBiometricTemplate($biometricData);
            
            // Find matching biometric records
            $matches = \DB::table('biometric_data')
                ->where('user_id', $userId)
                ->where('biometric_type', $biometricType)
                ->where('is_active', true)
                ->get();
            
            $bestMatch = null;
            $bestScore = 0;
            
            foreach ($matches as $match) {
                try {
                    $storedTemplate = json_decode(decrypt($match->encrypted_template), true);
                    $similarity = $this->calculateSimilarity($biometricData, $storedTemplate);
                    
                    if ($similarity > $bestScore) {
                        $bestScore = $similarity;
                        $bestMatch = $match;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
            
            // Threshold for match (adjust based on biometric type)
            $threshold = $this->getThreshold($biometricType);
            
            if ($bestScore >= $threshold) {
                // Log successful verification
                $this->logBiometricVerification($userId, $biometricType, true, $bestScore);
                
                return [
                    'success' => true,
                    'verified' => true,
                    'confidence' => $bestScore,
                    'message' => 'Biometric verification successful'
                ];
            }
            
            // Log failed verification
            $this->logBiometricVerification($userId, $biometricType, false, $bestScore);
            
            return [
                'success' => true,
                'verified' => false,
                'confidence' => $bestScore,
                'message' => 'Biometric verification failed'
            ];
        } catch (\Exception $e) {
            Log::error('Biometric verification failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Biometric verification error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Hash biometric template (one-way for comparison)
     */
    protected function hashBiometricTemplate(array $template): string
    {
        return hash('sha256', json_encode($template));
    }
    
    /**
     * Calculate similarity between two biometric templates
     * This is a simplified version - real implementations use advanced algorithms
     */
    protected function calculateSimilarity(array $template1, array $template2): float
    {
        // Simplified similarity calculation
        // In production, use proper biometric matching algorithms (e.g., minutiae matching for fingerprints)
        
        if (empty($template1) || empty($template2)) {
            return 0;
        }
        
        // Feature-based matching
        $matches = 0;
        $total = count($template1);
        
        foreach ($template1 as $key => $value) {
            if (isset($template2[$key])) {
                // For numeric features, check if within tolerance
                if (is_numeric($value) && is_numeric($template2[$key])) {
                    $diff = abs($value - $template2[$key]);
                    $tolerance = abs($value * 0.1); // 10% tolerance
                    if ($diff <= $tolerance) {
                        $matches++;
                    }
                } elseif ($value === $template2[$key]) {
                    $matches++;
                }
            }
        }
        
        return ($matches / $total) * 100;
    }
    
    /**
     * Get threshold for biometric type
     */
    protected function getThreshold(string $biometricType): float
    {
        $thresholds = [
            'fingerprint' => 85.0,
            'facial' => 90.0,
            'iris' => 95.0,
            'voice' => 80.0,
        ];
        
        return $thresholds[$biometricType] ?? 85.0;
    }
    
    /**
     * Log biometric verification attempt
     */
    protected function logBiometricVerification(string $userId, string $type, bool $success, float $score): void
    {
        \DB::table('biometric_verification_logs')->insert([
            'user_id' => $userId,
            'biometric_type' => $type,
            'success' => $success,
            'confidence_score' => $score,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }
    
    /**
     * Delete biometric data for a user
     */
    public function deleteBiometric(string $userId, ?string $biometricType = null): bool
    {
        try {
            $query = \DB::table('biometric_data')->where('user_id', $userId);
            
            if ($biometricType) {
                $query->where('biometric_type', $biometricType);
            }
            
            $query->update([
                'is_active' => false,
                'deleted_at' => now(),
                'updated_at' => now(),
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Biometric deletion failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get biometric statistics for a user
     */
    public function getBiometricStats(string $userId): array
    {
        $stats = \DB::table('biometric_verification_logs')
            ->where('user_id', $userId)
            ->selectRaw('
                COUNT(*) as total_attempts,
                SUM(CASE WHEN success = 1 THEN 1 ELSE 0 END) as successful,
                SUM(CASE WHEN success = 0 THEN 1 ELSE 0 END) as failed,
                AVG(confidence_score) as avg_confidence,
                MAX(created_at) as last_attempt
            ')
            ->first();
        
        return [
            'total_attempts' => $stats->total_attempts ?? 0,
            'successful' => $stats->successful ?? 0,
            'failed' => $stats->failed ?? 0,
            'success_rate' => $stats->total_attempts > 0 
                ? round(($stats->successful / $stats->total_attempts) * 100, 2) 
                : 0,
            'avg_confidence' => round($stats->avg_confidence ?? 0, 2),
            'last_attempt' => $stats->last_attempt ?? null,
        ];
    }
}

