<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Patient;
use App\Models\Employee;

class GenerateIdCard implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $model;
    public $type;

    /**
     * Create a new job instance.
     */
    public function __construct($model, string $type)
    {
        $this->model = $model;
        $this->type = $type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->type === 'patient') {
            $pdf = Pdf::loadView('hms.id-cards.patient-card', [
                'patient' => $this->model,
                'type' => 'Patient',
                'id' => $this->model->patient_no,
                'name' => $this->model->full_name,
                'dob' => $this->model->dob,
                'gender' => $this->model->gender,
                'photo' => null,
            ]);
            $pdf->setPaper([0, 0, 370, 240], 'portrait');
            $filename = 'batch_idcards/patient_' . $this->model->patient_no . '.pdf';
        } else {
            $themeSettings = [
                'hospital_logo' => \App\Models\SystemSetting::get('hospital_logo', ''),
                'hospital_name' => \App\Models\SystemSetting::get('hospital_name', 'DuncoHMS'),
            ];
            
            $pdf = Pdf::loadView('hms.id-cards.employee-card', [
                'employee' => $this->model,
                'type' => 'Staff',
                'id' => $this->model->employee_id,
                'name' => $this->model->full_name,
                'dob' => $this->model->date_of_birth,
                'gender' => $this->model->gender,
                'department' => $this->model->department->name ?? 'N/A',
                'position' => $this->model->position,
                'hire_date' => $this->model->hire_date,
                'photo' => null,
                'themeSettings' => $themeSettings,
            ]);
            $pdf->setPaper([0, 0, 370, 240], 'portrait');
            $filename = 'batch_idcards/employee_' . $this->model->employee_id . '.pdf';
        }

        Storage::disk('public')->put($filename, $pdf->output());
    }
}

