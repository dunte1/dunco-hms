<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'bio',
        'profile_photo_path',
        'status',
        'approved_by',
        'approved_at',
        'status_notes',
        'verification_code',
        'verification_code_expires_at',
        'branch_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_code',
        'verification_code_expires_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'approved_at' => 'datetime',
            'verification_code_expires_at' => 'datetime',
        ];
    }

    /**
     * Generate a 6-digit verification code and set expiry (10 minutes)
     */
    public function generateVerificationCode(): string
    {
        $code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        
        $this->update([
            'verification_code' => $code,
            'verification_code_expires_at' => now()->addMinutes(10),
        ]);

        return $code;
    }

    /**
     * Verify the OTP code
     */
    public function verifyCode(string $code): bool
    {
        if ($this->verification_code !== $code) {
            return false;
        }

        if ($this->verification_code_expires_at && $this->verification_code_expires_at->isPast()) {
            return false;
        }

        $this->update([
            'email_verified_at' => now(),
            'verification_code' => null,
            'verification_code_expires_at' => null,
            'status' => 'active',
            'approved_at' => now(),
        ]);

        return true;
    }

    /**
     * Check if verification code is expired
     */
    public function isCodeExpired(): bool
    {
        return $this->verification_code_expires_at && $this->verification_code_expires_at->isPast();
    }

    /**
     * Check if user has a pending verification code
     */
    public function hasPendingVerification(): bool
    {
        return !$this->email_verified_at && $this->verification_code !== null;
    }

    /**
     * Boot the model and set up event listeners
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically set status to 'active' when email is verified
        static::updating(function ($user) {
            // If email_verified_at is being set and status is still pending, auto-activate
            if ($user->isDirty('email_verified_at') && $user->email_verified_at && $user->status === 'pending') {
                // Only auto-activate if not manually set by admin
                if (!$user->isDirty('status')) {
                    $user->status = 'active';
                    // If approved_by is not set, set it to the system (or null for auto-activation)
                    if (!$user->approved_by && $user->exists) {
                        // Auto-approval via email verification - leave approved_by as null
                        $user->approved_at = now();
                    }
                }
            }
            
            // If status is being manually changed by admin, track who approved it
            if ($user->isDirty('status') && $user->status === 'active' && !$user->approved_at) {
                $user->approved_at = now();
                // Set approved_by only if not already set (to preserve original approver)
                if (!$user->approved_by && auth()->check()) {
                    $user->approved_by = auth()->id();
                }
            }
        });

        // Handle newly created users - set default status
        static::creating(function ($user) {
            if (!isset($user->status)) {
                $user->status = 'pending';
            }
        });
    }

    /**
     * Get the user who approved this account
     */
    public function approver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if user is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if user is inactive
     */
    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

    // Role and permission methods are now provided by HasRoles trait
    
    /**
     * Get the attendance records for the user.
     */
    public function attendance(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the employee record associated with this user.
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class, 'user_id');
    }

    public function branch(): HasOne
    {
        return $this->hasOne(HospitalBranch::class, 'id', 'branch_id');
    }
}
