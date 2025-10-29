<?php

namespace App\Exports;

use App\Models\Patient;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class PatientsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Patient::query();
        
        if ($this->request && $this->request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $this->request->date_from);
        }
        
        if ($this->request && $this->request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $this->request->date_to);
        }
        
        return $query->get();
    }

    /**
     * Define the headings for the Excel file
     */
    public function headings(): array
    {
        return [
            'ID',
            'Patient Number',
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Date of Birth',
            'Gender',
            'Address',
            'Created At',
        ];
    }

    /**
     * Map the patient data for each row
     */
    public function map($patient): array
    {
        return [
            $patient->id,
            $patient->patient_no,
            $patient->first_name,
            $patient->last_name,
            $patient->email,
            $patient->phone,
            $patient->date_of_birth ? $patient->date_of_birth->format('Y-m-d') : '',
            $patient->gender,
            $patient->address,
            $patient->created_at->format('Y-m-d H:i:s'),
        ];
    }
}

