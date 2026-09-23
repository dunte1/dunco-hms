<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RadiologyCategory;
use App\Models\RadiologyTest;
use App\Models\Ambulance;
use App\Models\OtRoom;
use App\Models\DrugInteraction;
use App\Models\Medicine;
use App\Models\ConsentForm;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\MrdFile;
use App\Models\Vaccine;
use App\Models\MortuaryRecord;
use App\Models\MedicalEquipment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CompleteDemoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        $doctor = Doctor::first();
        $patient = Patient::first();

        echo "Seeding Radiology Tests...\n";
        $this->seedRadiologyTests();

        echo "Seeding Ambulances...\n";
        $this->seedAmbulances();

        echo "Seeding OT Rooms...\n";
        $this->seedOtRooms();

        echo "Seeding Drug Interactions...\n";
        $this->seedDrugInteractions();

        echo "Seeding Consent Forms...\n";
        if ($patient && $doctor) {
            $this->seedConsentForms($patient, $doctor);
        }

        echo "Seeding MRD Files...\n";
        if ($patient) {
            $this->seedMrdFiles($patient);
        }

        echo "Seeding Vaccines...\n";
        $this->seedVaccines();

        echo "Seeding Mortuary Records...\n";
        if ($patient && $admin) {
            $this->seedMortuaryRecords($patient, $admin);
        }

        echo "Seeding Medical Equipment...\n";
        $this->seedMedicalEquipment();

        echo "\n=== ALL DEMO DATA SEEDED SUCCESSFULLY ===\n";
    }

    private function seedRadiologyTests(): void
    {
        $categories = ['Imaging', 'X-Ray', 'CT Scan', 'MRI', 'Ultrasound'];
        foreach ($categories as $cat) {
            RadiologyCategory::firstOrCreate(['name' => $cat], ['description' => "{$cat} department"]);
        }

        $tests = [
            ['X-Ray Chest PA', 'Imaging', 800],
            ['X-Ray Chest Lateral', 'Imaging', 800],
            ['X-Ray Abdomen', 'Imaging', 900],
            ['X-Ray Skull', 'Imaging', 850],
            ['X-Ray Spine', 'Imaging', 900],
            ['X-Ray Pelvis', 'Imaging', 850],
            ['CT Scan Head', 'CT Scan', 5000],
            ['CT Scan Abdomen', 'CT Scan', 6000],
            ['CT Scan Chest', 'CT Scan', 5500],
            ['CT Scan Spine', 'CT Scan', 5500],
            ['MRI Brain', 'MRI', 12000],
            ['MRI Spine', 'MRI', 12000],
            ['MRI Knee', 'MRI', 10000],
            ['MRI Abdomen', 'MRI', 11000],
            ['Ultrasound Abdomen', 'Ultrasound', 3000],
            ['Ultrasound Pelvis', 'Ultrasound', 3000],
            ['Ultrasound Obstetric', 'Ultrasound', 3500],
            ['Ultrasound Thyroid', 'Ultrasound', 2500],
            ['Mammography', 'Imaging', 4000],
            ['DEXA Scan', 'Imaging', 5000],
        ];

        foreach ($tests as $test) {
            RadiologyTest::create([
                'test_name' => $test[0],
                'category_id' => RadiologyCategory::where('name', $test[1])->first()->id,
                'price' => $test[2],
                'description' => "Standard {$test[0]} procedure",
                'is_active' => true,
                'preparation_instructions' => 'Follow specific preparation instructions.',

            ]);
        }
    }

    private function seedAmbulances(): void
    {
        $ambulances = [
            ['KBA-001', 'James Omondi', '0712345678', 'advanced', 'Full cardiac, oxygen, stretcher, first aid kit'],
            ['KBA-002', 'Peter Kamau', '0723456789', 'basic', 'Stretcher, oxygen, first aid kit'],
            ['KBA-003', 'Mary Wanjiku', '0734567890', 'critical_care', 'Ventilator, IV pump, monitor, defibrillator, stretcher'],
            ['KBA-004', 'John Kipchoge', '0745678901', 'advanced', 'Cardiac monitor, oxygen, stretcher, spinal board'],
            ['KBA-005', 'Grace Akinyi', '0756789012', 'basic', 'Stretcher, oxygen, first aid kit, wheelchair'],
            ['KBA-006', 'David Mutua', '0767890123', 'advanced', 'Full cardiac, oxygen, stretcher, AED'],
        ];

        foreach ($ambulances as $amb) {
            Ambulance::create([
                'vehicle_number' => $amb[0],
                'driver_name' => $amb[1],
                'driver_phone' => $amb[2],
                'vehicle_type' => $amb[3],
                'equipment_list' => $amb[4],
                'is_available' => true,
                'status' => 'active',
            ]);
        }
    }

    private function seedOtRooms(): void
    {
        $rooms = [
            ['OT-1', 'Ground Floor', 'general', 'Standard general surgery', 'available'],
            ['OT-2', 'Ground Floor', 'general', 'Standard general surgery', 'available'],
            ['OT-3', 'Ground Floor', 'cardiac', 'Cardiac surgery suite with perfusion machine', 'available'],
            ['OT-4', 'Ground Floor', 'neuro', 'Neurosurgery suite with C-arm', 'maintenance'],
            ['OT-5', 'First Floor', 'orthopedic', 'Orthopedic surgery with fluoroscopy', 'available'],
            ['OT-EM', 'Emergency', 'emergency', 'Emergency trauma OT', 'available'],
            ['OT-PED', 'First Floor', 'pediatric', 'Pediatric surgery suite', 'available'],
            ['OT-EYE', 'First Floor', 'ophthalmic', 'Ophthalmic surgery with microscope', 'available'],
        ];

        foreach ($rooms as $room) {
            OtRoom::create([
                'name' => $room[0],
                'floor' => $room[1],
                'type' => $room[2],
                'equipment_notes' => $room[3],
                'status' => $room[4],
                'capacity' => 1,
            ]);
        }
    }

    private function seedDrugInteractions(): void
    {
        $interactions = [
            ['Warfarin', 'Aspirin', 'critical', 'Increased risk of bleeding when taken together', 'Significantly increased bleeding risk', 'Avoid combination. Use alternative antiplatelet.'],
            ['Warfarin', 'Ibuprofen', 'critical', 'NSAIDs increase bleeding risk with warfarin', 'Enhanced anticoagulation and GI bleeding', 'Use paracetamol instead of ibuprofen.'],
            ['Metformin', 'Alcohol', 'severe', 'Alcohol increases risk of lactic acidosis', 'Lactic acidosis, hypoglycemia', 'Limit alcohol intake. Monitor blood glucose.'],
            ['Lisinopril', 'Potassium', 'severe', 'ACE inhibitors increase potassium levels', 'Hyperkalemia', 'Monitor serum potassium regularly.'],
            ['Simvastatin', 'Clarithromycin', 'severe', 'Macrolide antibiotics increase statin levels', 'Rhabdomyolysis risk', 'Reduce statin dose or use alternative antibiotic.'],
            ['Amoxicillin', 'Metronidazole', 'moderate', 'May increase GI side effects', 'Nausea, diarrhea', 'Monitor patient for GI symptoms.'],
            ['Paracetamol', 'Warfarin', 'moderate', 'High doses may enhance anticoagulant effect', 'Increased INR', 'Monitor INR if high-dose paracetamol used.'],
            ['Omeprazole', 'Clopidogrel', 'severe', 'PPIs reduce antiplatelet effect of clopidogrel', 'Reduced cardiovascular protection', 'Use pantoprazole instead of omeprazole.'],
            ['Ciprofloxacin', 'Theophylline', 'severe', 'Fluoroquinolones increase theophylline levels', 'Theophylline toxicity', 'Monitor theophylline levels. Consider alternative antibiotic.'],
            ['Fluconazole', 'Warfarin', 'severe', 'Azole antifungals potentiate warfarin', 'Significantly increased INR', 'Reduce warfarin dose. Monitor INR closely.'],
            ['Metformin', 'Iodinated Contrast', 'severe', 'Contrast media increases risk of lactic acidosis', 'Acute kidney injury, lactic acidosis', 'Hold metformin 48 hours before and after contrast.'],
            ['Aspirin', 'Ibuprofen', 'moderate', 'Dual antiplatelet/NSAID increases GI bleeding', 'GI ulceration and bleeding', 'Avoid concurrent use. Use paracetamol for pain.'],
            ['Digoxin', 'Amiodarone', 'severe', 'Amiodarone increases digoxin levels by 70-100%', 'Digoxin toxicity', 'Reduce digoxin dose by 50% when starting amiodarone.'],
            ['Lithium', 'Ibuprofen', 'severe', 'NSAIDs reduce lithium clearance', 'Lithium toxicity', 'Avoid NSAIDs. Use paracetamol.'],
            ['Phenytoin', 'Fluconazole', 'moderate', 'Azole antifungals increase phenytoin levels', 'Phenytoin toxicity', 'Monitor phenytoin levels closely.'],
        ];

        foreach ($interactions as $int) {
            $drugA = Medicine::where('name', 'like', "%{$int[0]}%")->first();
            $drugB = Medicine::where('name', 'like', "%{$int[1]}%")->first();
            if ($drugA && $drugB) {
                DrugInteraction::create([
                    'drug_a_id' => $drugA->id,
                    'drug_b_id' => $drugB->id,
                    'severity' => $int[2],
                    'description' => $int[3],
                    'clinical_effect' => $int[4],
                    'management_advice' => $int[5],
                    'source' => 'DrugBank/Medscape',
                    'is_active' => true,
                ]);
            }
        }
    }

    private function seedConsentForms($patient, $doctor): void
    {
        $types = ['procedure', 'anesthesia', 'blood_transfusion'];
        foreach ($types as $i => $type) {
            ConsentForm::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'consent_type' => $type,
                'procedure_name' => ucfirst(str_replace('_', ' ', $type)) . ' Consent',
                'description' => "Standard consent form for {$type}",
                'risks_disclosed' => 'Standard risks associated with the procedure have been explained.',
                'alternatives_disclosed' => 'Alternative treatment options have been discussed.',
                'status' => $i === 0 ? 'signed' : 'pending',
                'signed_at' => $i === 0 ? now()->subDays(5) : null,
            ]);
        }
    }

    private function seedMrdFiles($patient): void
    {
        $types = ['discharge_summary', 'lab_report', 'imaging', 'consent', 'operation_note'];
        foreach ($types as $i => $type) {
            MrdFile::create([
                'patient_id' => $patient->id,
                'file_number' => MrdFile::generateFileNumber(),
                'file_type' => $type,
                'physical_location' => 'Shelf ' . ($i + 1) . ', Room 101',
                'status' => $i < 3 ? 'in_library' : 'issued',
            ]);
        }
    }

    private function seedVaccines(): void
    {
        $vaccines = [
            ['BCG Vaccine', 'Serum Institute of India', 1, 50, 'BCG-001', 150],
            ['OPV (Oral Polio)', 'Serum Institute of India', 3, 200, 'OPV-001', 50],
            ['Pentavalent Vaccine', 'Serum Institute of India', 3, 150, 'PENTA-001', 200],
            ['MMR Vaccine', 'Serum Institute of India', 2, 80, 'MMR-001', 500],
            ['Hepatitis B Vaccine', 'Serum Institute of India', 3, 120, 'HEPB-001', 300],
            ['Tetanus Vaccine', 'Serum Institute of India', 5, 100, 'TET-001', 100],
            ['COVID-19 Vaccine (Pfizer)', 'Pfizer', 2, 300, 'CV-001', 2000],
            ['Influenza Vaccine', 'Sanofi', 1, 200, 'FLU-001', 800],
            ['Rabies Vaccine', 'Serum Institute of India', 4, 60, 'RAB-001', 1500],
            ['HPV Vaccine', 'Merck', 3, 40, 'HPV-001', 5000],
        ];

        foreach ($vaccines as $v) {
            Vaccine::create([
                'name' => $v[0],
                'manufacturer' => $v[1],
                'dose_count' => $v[2],
                'stock_quantity' => $v[3],
                'expiry_date' => now()->addMonths(rand(6, 24)),
                'batch_number' => $v[4],
                'cost' => $v[5],
            ]);
        }
    }

    private function seedMortuaryRecords($patient, $admin): void
    {
        $records = [
            ['MORT-001', 'Cardiac arrest', 'Cabinet A-1', 'John Doe Family', '0712345678'],
            ['MORT-002', 'Road traffic accident', 'Cabinet A-2', 'Jane Smith Family', '0723456789'],
            ['MORT-003', 'Stroke', 'Cabinet B-1', 'Peter Kim Family', '0734567890'],
        ];

        foreach ($records as $rec) {
            MortuaryRecord::create([
                'body_id' => $rec[0],
                'received_at' => now()->subDays(rand(1, 10)),
                'received_by' => $admin->id,
                'storage_location' => $rec[2],
                'cause_of_death' => $rec[1],
                'status' => 'stored',
                'family_contact_name' => $rec[3],
                'family_contact_phone' => $rec[4],
                'identification_method' => 'Photo ID and family identification',
            ]);
        }
    }

    private function seedMedicalEquipment(): void
    {
        $equipment = [
            ['Philips MX800 Patient Monitor', 'life_support', 'ICU', 'PHILIPS-MX800', 'Philips', 'operational'],
            ['Siemens Artis Zee C-Arm', 'diagnostic', 'Radiology', 'SIEMENS-AZ', 'Siemens', 'operational'],
            ['GE Logiq E9 Ultrasound', 'diagnostic', 'Radiology', 'GE-LOGIQ-E9', 'GE Healthcare', 'operational'],
            ['Roche Cobas 6800 Analyzer', 'laboratory', 'Laboratory', 'ROCHE-COBAS-6800', 'Roche', 'operational'],
            ['Siemens Advia 2120i', 'laboratory', 'Laboratory', 'SIEMENS-ADVIA', 'Siemens', 'operational'],
            ['Mindray A5 Anesthesia Machine', 'life_support', 'OT', 'MINDRAY-A5', 'Mindray', 'operational'],
            ['Drager Primus Anesthesia', 'life_support', 'OT', 'DRAGER-PRIMUS', 'Drager', 'maintenance'],
            ['Olympus CV-190 Endoscope', 'surgical', 'OT', 'OLYMPUS-CV190', 'Olympus', 'operational'],
            ['Stryker 1588 Camera System', 'surgical', 'OT', 'STRYKER-1588', 'Stryker', 'operational'],
            ['Nihon Kohden EEG Monitor', 'diagnostic', 'Neurology', 'NIHON-EEG', 'Nihon Kohden', 'operational'],
            ['Masimo SET Pulse Oximeter', 'life_support', 'ICU', 'MASIMO-SET', 'Masimo', 'operational'],
            ['Medela Breast Pump', 'therapeutic', 'Maternity', 'MEDELA-150', 'Medela', 'operational'],
            ['Welch Allyn Vital Signs Monitor', 'diagnostic', 'Emergency', 'WELCH-VS', 'Welch Allyn', 'operational'],
            ['BD Syringe Pump', 'life_support', 'ICU', 'BD-50', 'Becton Dickinson', 'operational'],
            ['Misonix Sonicision', 'surgical', 'OT', 'MISONIX-SC', 'Misonix', 'out_of_service'],
        ];

        foreach ($equipment as $eq) {
            MedicalEquipment::create([
                'name' => $eq[0],
                'category' => $eq[1],
                'department' => $eq[2],
                'serial_number' => $eq[3],
                'manufacturer' => $eq[4],
                'purchase_date' => now()->subYears(rand(1, 5)),
                'warranty_expiry' => now()->addMonths(rand(6, 36)),
                'status' => $eq[5],
                'location' => $eq[2],
                'current_value' => rand(5000, 500000),
            ]);
        }
    }
}
