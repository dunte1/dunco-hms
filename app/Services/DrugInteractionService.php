<?php

namespace App\Services;

use App\Models\DrugInteraction;
use App\Models\Medicine;
use App\Models\PatientAllergy;

class DrugInteractionService
{
    public function checkInteractions(array $drugIds): array
    {
        if (count($drugIds) < 2) {
            return [];
        }

        $warnings = [];
        $pairs = $this->getCombinations($drugIds);

        foreach ($pairs as [$drugAId, $drugBId]) {
            $interaction = DrugInteraction::where('is_active', true)
                ->where(function ($query) use ($drugAId, $drugBId) {
                    $query->where(function ($q) use ($drugAId, $drugBId) {
                        $q->where('drug_a_id', $drugAId)->where('drug_b_id', $drugBId);
                    })->orWhere(function ($q) use ($drugAId, $drugBId) {
                        $q->where('drug_a_id', $drugBId)->where('drug_b_id', $drugAId);
                    });
                })
                ->first();

            if ($interaction) {
                $drugA = Medicine::find($drugAId);
                $drugB = Medicine::find($drugBId);
                $warnings[] = [
                    'severity' => $interaction->severity,
                    'drug_a' => $drugA->name ?? "Drug #{$drugAId}",
                    'drug_b' => $drugB->name ?? "Drug #{$drugBId}",
                    'description' => $interaction->description,
                    'clinical_effect' => $interaction->clinical_effect,
                    'management_advice' => $interaction->management_advice,
                ];
            }
        }

        usort($warnings, function ($a, $b) {
            $order = ['critical' => 0, 'severe' => 1, 'moderate' => 2, 'mild' => 3];
            return ($order[$a['severity']] ?? 4) - ($order[$b['severity']] ?? 4);
        });

        return $warnings;
    }

    public function checkAllergies(int $patientId, array $drugIds): array
    {
        $allergies = PatientAllergy::where('patient_id', $patientId)
            ->where('is_active', true)
            ->get();

        if ($allergies->isEmpty()) {
            return [];
        }

        $alerts = [];
        $drugs = Medicine::whereIn('id', $drugIds)->get();

        foreach ($drugs as $drug) {
            foreach ($allergies as $allergy) {
                if ($this->isAllergenMatch($drug, $allergy)) {
                    $alerts[] = [
                        'severity' => $allergy->severity,
                        'drug' => $drug->name,
                        'allergen' => $allergy->allergen,
                        'reaction' => $allergy->reaction,
                        'allergen_type' => $allergy->allergen_type,
                    ];
                }
            }
        }

        return $alerts;
    }

    public function getContraindications(int $drugId): array
    {
        $interactions = DrugInteraction::where('is_active', true)
            ->where(function ($query) use ($drugId) {
                $query->where('drug_a_id', $drugId)->orWhere('drug_b_id', $drugId);
            })
            ->with(['drugA', 'drugB'])
            ->get();

        return $interactions->map(function ($interaction) use ($drugId) {
            $otherDrug = $interaction->drug_a_id === $drugId ? $interaction->drugB : $interaction->drugA;
            return [
                'severity' => $interaction->severity,
                'other_drug' => $otherDrug->name ?? 'Unknown',
                'description' => $interaction->description,
                'management_advice' => $interaction->management_advice,
            ];
        })->toArray();
    }

    public function getSeverityLevel(string $severity): string
    {
        return match($severity) {
            'critical' => 'STOP - Do not co-prescribe',
            'severe' => 'AVOID - Use alternative if possible',
            'moderate' => 'MONITOR - Increased monitoring required',
            'mild' => 'CAUTION - Low risk, monitor patient',
            default => 'UNKNOWN',
        };
    }

    private function getCombinations(array $ids): array
    {
        $combinations = [];
        $count = count($ids);
        for ($i = 0; $i < $count - 1; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                $combinations[] = [$ids[$i], $ids[$j]];
            }
        }
        return $combinations;
    }

    private function isAllergenMatch(Medicine $drug, PatientAllergy $allergy): bool
    {
        $drugName = strtolower($drug->name);
        $allergen = strtolower($allergy->allergen);

        if (str_contains($drugName, $allergen) || str_contains($allergen, $drugName)) {
            return true;
        }

        if ($drug->active_ingredient && str_contains(strtolower($drug->active_ingredient), $allergen)) {
            return true;
        }

        if ($allergy->allergen_type === 'drug') {
            $aliases = $this->getDrugAliases($allergen);
            foreach ($aliases as $alias) {
                if (str_contains($drugName, $alias)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function getDrugAliases(string $allergen): array
    {
        $commonAliases = [
            'penicillin' => ['amoxicillin', 'ampicillin', 'amoxicil'],
            'aspirin' => ['acetylsalicylic', 'asa', 'salicyl'],
            'sulfonamide' => ['sulfa', 'sulfamethoxazole', 'sulfadiazine'],
            'ibuprofen' => ['advil', 'motrin', 'brufen'],
            'paracetamol' => ['acetaminophen', 'tylenol', 'panadol'],
            'metformin' => ['glucophage', 'glycomet'],
        ];

        return $commonAliases[$allergen] ?? [];
    }
}
