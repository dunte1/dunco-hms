<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Models\Scopes\BelongsToFacility;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Patient extends Model
{
    use HasFactory, Auditable, Notifiable, SoftDeletes, BelongsToFacility;

    protected $fillable = [
        'patient_no','first_name','last_name','dob','gender','email','phone','address',
        'national_id','dha_cr_id','dha_verified_at','nationality','county','sub_county','ward',
        'created_by','updated_by','facility_id',
    ];

    protected $casts = [
        'dob' => 'date',
        'dha_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($patient) {
            if (empty($patient->patient_no)) {
                $patient->patient_no = 'PAT' . str_pad(Patient::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    protected $appends = ['full_name'];

    /**
     * Bounded option list for dropdowns (avoids loading entire patient table).
     * Usage: Patient::forSelect()  → Eloquent Collection
     *        Patient::search($q)->forSelect() is NOT chainable after get();
     *        use Patient::search($q)->limit(50)->get([...]) for AJAX lists.
     */
    public function scopeForSelect($query)
    {
        return $query->orderBy('first_name')
            ->orderBy('last_name')
            ->limit(500)
            ->get(['id', 'first_name', 'last_name', 'patient_no', 'phone']);
    }

    public function scopeSearch($query, ?string $term)
    {
        if ($term === null || trim($term) === '') {
            return $query;
        }

        $like = '%' . trim($term) . '%';

        return $query->where(function ($q) use ($like) {
            $q->where('first_name', 'like', $like)
                ->orWhere('last_name', 'like', $like)
                ->orWhere('patient_no', 'like', $like)
                ->orWhere('phone', 'like', $like)
                ->orWhere('email', 'like', $like)
                ->orWhere('national_id', 'like', $like);
        });
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }

    public function medicalHistories()
    {
        return $this->hasMany(MedicalHistory::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function ipdAdmissions()
    {
        return $this->hasMany(IpdAdmission::class);
    }

    public function opdVisits()
    {
        return $this->hasMany(OpdVisit::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function patientInsurances()
    {
        return $this->hasMany(PatientInsurance::class);
    }

    public function insurance()
    {
        return $this->hasMany(PatientInsurance::class);
    }

    public function hasInsurance(): bool
    {
        return $this->insurance()->where('is_active', true)->exists();
    }

    public function identifiers(): HasMany
    {
        return $this->hasMany(PatientIdentifier::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(PatientContact::class);
    }

    public function mergeLogPrimary(): HasMany
    {
        return $this->hasMany(PatientMergeLog::class, 'primary_patient_id');
    }

    public function mergeLogDuplicates(): HasMany
    {
        return $this->hasMany(PatientMergeLog::class, 'duplicate_patient_id');
    }

    public function mergeWith(int $duplicateId): PatientMergeLog
    {
        $duplicate = static::findOrFail($duplicateId);

        $relatedTables = [
            'appointments' => 'patient_id',
            'ipd_admissions' => 'patient_id',
            'opd_visits' => 'patient_id',
            'prescriptions' => 'patient_id',
            'patient_insurances' => 'patient_id',
            'medical_histories' => 'patient_id',
            'patient_identifiers' => 'patient_id',
            'patient_contacts' => 'patient_id',
        ];

        foreach ($relatedTables as $table => $column) {
            if (\Illuminate\Support\Facades\Schema::hasTable($table)) {
                \Illuminate\Support\Facades\DB::table($table)
                    ->where($column, $duplicate->id)
                    ->update([$column => $this->id]);
            }
        }

        $duplicate->delete();

        return PatientMergeLog::create([
            'primary_patient_id' => $this->id,
            'duplicate_patient_id' => $duplicateId,
            'merged_by' => auth()->id(),
            'merge_reason' => 'Duplicate patient merged into primary',
            'data_migrated' => true,
            'merged_at' => now(),
        ]);
    }

    public function detectDuplicates(array $attributes): \Illuminate\Database\Eloquent\Collection
    {
        $query = static::query()->where('id', '!=', $this->id ?? 0);

        if (!empty($attributes['first_name']) && !empty($attributes['last_name']) && !empty($attributes['dob'])) {
            $query->where(function ($q) use ($attributes) {
                $q->where('first_name', $attributes['first_name'])
                  ->where('last_name', $attributes['last_name'])
                  ->where('dob', $attributes['dob']);
            });
        }

        if (!empty($attributes['national_id'])) {
            $query->orWhere('national_id', $attributes['national_id']);
        }

        return $query->get();
    }
}



