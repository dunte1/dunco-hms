<?php

namespace App\Models;

use App\Models\Scopes\BelongsToFacility;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

/**
 * Facility-scoped clinical models: apply BelongsToFacility + Auditable
 * where the table has facility_id (from audit-columns migration).
 *
 * This file documents the intended set; individual models are updated
 * in place by the implementation pass.
 */
class FacilityScopedClinicalModels
{
    public const MODELS = [
        'Prescription',
        'LabRequest',
        'RadiologyRequest',
        'Invoice',
        'IpdAdmission',
        'ConsentForm',
        'MrdFile',
        'VaccinationRecord',
        'MortuaryRecord',
        'PatientDiagnosis',
        'Referral',
        'Payment',
    ];
}
