<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Nurse;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\MedicineBrand;
use App\Models\LabTest;
use App\Models\LabCategory;
use App\Models\BedType;
use App\Models\Bed;
use App\Models\OpdVisit;
use App\Models\InsuranceProvider;
use App\Models\PatientInsurance;
use App\Models\EmployeeDepartment;
use App\Models\Designation;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Income;
use App\Models\BloodGroup;
use App\Models\BloodDonor;
use App\Models\QueueManagement;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CompleteDemoSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('');
        $this->command->info('==============================================');
        $this->command->info('  DUNCO HMS - COMPLETE DEMO DATA SEEDER');
        $this->command->info('==============================================');
        $this->command->info('');

        // 1. Roles & Permissions
        $this->command->info('[1/15] Seeding roles and permissions...');
        $this->seedRolesAndPermissions();

        // 2. Admin Users
        $this->command->info('[2/15] Creating admin users...');
        $this->seedAdminUsers();

        // 3. Staff Users (Doctors, Nurses, etc.)
        $this->command->info('[3/15] Creating staff users...');
        $this->seedStaffUsers();

        // 4. Patient Users
        $this->command->info('[4/15] Creating patient users...');
        $patientUsers = $this->seedPatientUsers();

        // 5. Doctor Records
        $this->command->info('[5/15] Creating doctor records...');
        $doctors = $this->seedDoctorRecords();

        // 6. Nurse Records
        $this->command->info('[6/15] Creating nurse records...');
        $nurses = $this->seedNurseRecords();

        // 7. Patient Records
        $this->command->info('[7/15] Creating patient records...');
        $patients = $this->seedPatientRecords($patientUsers);

        // 8. Departments & Designations
        $this->command->info('[8/15] Creating departments and designations...');
        $this->seedDepartments();

        // 9. Beds & Wards
        $this->command->info('[9/15] Creating beds and wards...');
        $this->seedBeds();

        // 10. Medicines & Pharmacy
        $this->command->info('[10/15] Creating medicine catalog...');
        $medicines = $this->seedMedicines();

        // 11. Lab Tests
        $this->command->info('[11/15] Creating lab test catalog...');
        $labTests = $this->seedLabTests();

        // 12. Appointments & Visits
        $this->command->info('[12/15] Creating appointments and visits...');
        $this->seedAppointments($patients, $doctors);

        // 13. Billing & Payments
        $this->command->info('[13/15] Creating invoices and payments...');
        $this->seedBilling($patients);

        // 14. Insurance
        $this->command->info('[14/15] Creating insurance records...');
        $this->seedInsurance($patients);

        // 15. Blood Bank & Other
        $this->command->info('[15/15] Creating blood bank and misc data...');
        $this->seedMiscData();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('');
        $this->command->info('==============================================');
        $this->command->info('  SEEDING COMPLETE!');
        $this->command->info('==============================================');
        $this->command->info('');
        $this->printSummary();
    }

    private function seedRolesAndPermissions(): void
    {
        // Run the existing roles and permissions seeder
        $this->call(RolesAndPermissionsSeeder::class);
    }

    private function seedAdminUsers(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@duncohms.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles(['Super Admin']);

        $hospitalAdmin = User::firstOrCreate(
            ['email' => 'hospital@duncohms.com'],
            [
                'name' => 'Hospital Administrator',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );
        $hospitalAdmin->syncRoles(['Hospital Admin']);
    }

    private function seedStaffUsers(): array
    {
        $faker = Faker::create();
        $staff = [];

        // Doctors
        $doctorData = [
            ['name' => 'Dr. James Mwangi', 'email' => 'dr.mwangi@duncohms.com', 'phone' => '+254712345001'],
            ['name' => 'Dr. Amina Hassan', 'email' => 'dr.hassan@duncohms.com', 'phone' => '+254712345002'],
            ['name' => 'Dr. Peter Ochieng', 'email' => 'dr.ochieng@duncohms.com', 'phone' => '+254712345003'],
            ['name' => 'Dr. Grace Wanjiku', 'email' => 'dr.wanjiku@duncohms.com', 'phone' => '+254712345004'],
            ['name' => 'Dr. Samuel Kiprop', 'email' => 'dr.kiprop@duncohms.com', 'phone' => '+254712345005'],
        ];

        foreach ($doctorData as $doc) {
            $user = User::firstOrCreate(
                ['email' => $doc['email']],
                [
                    'name' => $doc['name'],
                    'password' => Hash::make('doctor123'),
                    'phone' => $doc['phone'],
                    'email_verified_at' => now(),
                ]
            );
            $user->syncRoles(['Doctor']);
            $staff[] = $user;
        }

        // Nurses
        $nurseData = [
            ['name' => 'Nurse Faith Njeri', 'email' => 'nurse.njeri@duncohms.com', 'phone' => '+254712345010'],
            ['name' => 'Nurse Ruth Akinyi', 'email' => 'nurse.akinyi@duncohms.com', 'phone' => '+254712345011'],
            ['name' => 'Nurse Esther Wambui', 'email' => 'nurse.wambui@duncohms.com', 'phone' => '+254712345012'],
            ['name' => 'Nurse Lucy Anyango', 'email' => 'nurse.anyango@duncohms.com', 'phone' => '+254712345013'],
            ['name' => 'Nurse Mary Muthoni', 'email' => 'nurse.muthoni@duncohms.com', 'phone' => '+254712345014'],
        ];

        foreach ($nurseData as $nur) {
            $user = User::firstOrCreate(
                ['email' => $nur['email']],
                [
                    'name' => $nur['name'],
                    'password' => Hash::make('nurse123'),
                    'phone' => $nur['phone'],
                    'email_verified_at' => now(),
                ]
            );
            $user->syncRoles(['Nurse']);
            $staff[] = $user;
        }

        // Receptionists
        $receptionData = [
            ['name' => 'Sarah Kimani', 'email' => 'reception.kimani@duncohms.com', 'phone' => '+254712345020'],
            ['name' => 'Daniel Mutua', 'email' => 'reception.mutua@duncohms.com', 'phone' => '+254712345021'],
        ];

        foreach ($receptionData as $rec) {
            $user = User::firstOrCreate(
                ['email' => $rec['email']],
                [
                    'name' => $rec['name'],
                    'password' => Hash::make('reception123'),
                    'phone' => $rec['phone'],
                    'email_verified_at' => now(),
                    'status' => 'active',
                ]
            );
            $user->syncRoles(['Receptionist']);
            $staff[] = $user;
        }

        // Pharmacists
        $pharmacistData = [
            ['name' => 'Kevin Otieno', 'email' => 'pharmacist.otieno@duncohms.com', 'phone' => '+254712345030'],
            ['name' => 'Nancy Chebet', 'email' => 'pharmacist.chebet@duncohms.com', 'phone' => '+254712345031'],
        ];

        foreach ($pharmacistData as $pharm) {
            $user = User::firstOrCreate(
                ['email' => $pharm['email']],
                [
                    'name' => $pharm['name'],
                    'password' => Hash::make('pharmacist123'),
                    'phone' => $pharm['phone'],
                    'email_verified_at' => now(),
                    'status' => 'active',
                ]
            );
            $user->syncRoles(['Pharmacist']);
            $staff[] = $user;
        }

        // Lab Technicians
        $labData = [
            ['name' => 'Brian Wekesa', 'email' => 'lab.wekesa@duncohms.com', 'phone' => '+254712345040'],
            ['name' => 'Alice Ndunge', 'email' => 'lab.ndunge@duncohms.com', 'phone' => '+254712345041'],
        ];

        foreach ($labData as $lab) {
            $user = User::firstOrCreate(
                ['email' => $lab['email']],
                [
                    'name' => $lab['name'],
                    'password' => Hash::make('labtech123'),
                    'phone' => $lab['phone'],
                    'email_verified_at' => now(),
                    'status' => 'active',
                ]
            );
            $user->syncRoles(['Lab Technician']);
            $staff[] = $user;
        }

        // Accountants
        $accountantData = [
            ['name' => 'Patricia Ogutu', 'email' => 'accountant.ogutu@duncohms.com', 'phone' => '+254712345050'],
        ];

        foreach ($accountantData as $acc) {
            $user = User::firstOrCreate(
                ['email' => $acc['email']],
                [
                    'name' => $acc['name'],
                    'password' => Hash::make('accountant123'),
                    'phone' => $acc['phone'],
                    'email_verified_at' => now(),
                    'status' => 'active',
                ]
            );
            $user->syncRoles(['Accountant']);
            $staff[] = $user;
        }

        // HR Officer
        $hrUser = User::firstOrCreate(
            ['email' => 'hr@duncohms.com'],
            [
                'name' => 'Michael Froma',
                'password' => Hash::make('hr123'),
                'phone' => '+254712345060',
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );
        $hrUser->syncRoles(['HR Officer']);
        $staff[] = $hrUser;

        // Inventory Manager
        $invUser = User::firstOrCreate(
            ['email' => 'inventory@duncohms.com'],
            [
                'name' => 'Joseph Kiplagat',
                'password' => Hash::make('inventory123'),
                'phone' => '+254712345070',
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );
        $invUser->syncRoles(['Inventory Manager']);
        $staff[] = $invUser;

        $this->command->info('   Created ' . count($staff) . ' staff users');
        return $staff;
    }

    private function seedPatientUsers(): array
    {
        $faker = Faker::create();
        $patientUsers = [];

        $patientUserData = [
            ['name' => 'John Kamau', 'email' => 'patient.kamau@duncohms.com', 'phone' => '+254722000001'],
            ['name' => 'Mary Wanjiru', 'email' => 'patient.wanjiru@duncohms.com', 'phone' => '+254722000002'],
            ['name' => 'Peter Odhiambo', 'email' => 'patient.odhiambo@duncohms.com', 'phone' => '+254722000003'],
            ['name' => 'Grace Mutua', 'email' => 'patient.mutua@duncohms.com', 'phone' => '+254722000004'],
            ['name' => 'David Kipchoge', 'email' => 'patient.kipchoge@duncohms.com', 'phone' => '+254722000005'],
            ['name' => 'Sarah Akinyi', 'email' => 'patient.akinyi@duncohms.com', 'phone' => '+254722000006'],
            ['name' => 'Michael Njoroge', 'email' => 'patient.njoroge@duncohms.com', 'phone' => '+254722000007'],
            ['name' => 'Lucy Wambui', 'email' => 'patient.wambui@duncohms.com', 'phone' => '+254722000008'],
            ['name' => 'Joseph Maina', 'email' => 'patient.maina@duncohms.com', 'phone' => '+254722000009'],
            ['name' => 'Esther Njeri', 'email' => 'patient.njeri@duncohms.com', 'phone' => '+254722000010'],
        ];

        foreach ($patientUserData as $pu) {
            $user = User::firstOrCreate(
                ['email' => $pu['email']],
                [
                    'name' => $pu['name'],
                    'password' => Hash::make('patient123'),
                    'phone' => $pu['phone'],
                    'email_verified_at' => now(),
                    'status' => 'active',
                ]
            );
            $user->syncRoles(['Patient']);
            $patientUsers[] = $user;
        }

        $this->command->info('   Created ' . count($patientUsers) . ' patient users');
        return $patientUsers;
    }

    private function seedDoctorRecords(): array
    {
        $faker = Faker::create();
        $doctors = [];
        $specialties = ['General Medicine', 'Pediatrics', 'Cardiology', 'Orthopedics', 'Dermatology'];
        $doctorUsers = User::role('Doctor')->get();

        foreach ($doctorUsers as $i => $user) {
            $doctor = Doctor::create([
                'first_name' => explode(' ', $user->name)[1] ?? $user->name,
                'last_name' => explode(' ', $user->name)[2] ?? '',
                'email' => $user->email,
                'phone' => $user->phone ?? $faker->phoneNumber,
                'qualification' => $faker->randomElement(['MBChB', 'MD', 'MBBS', 'FRCS']),
                'years_experience' => $faker->numberBetween(2, 25),
            ]);
            $doctors[] = $doctor;
        }

        $this->command->info('   Created ' . count($doctors) . ' doctor records');
        return $doctors;
    }

    private function seedNurseRecords(): array
    {
        $faker = Faker::create();
        $nurses = [];
        $nurseUsers = User::role('Nurse')->get();

        // Ensure nurse departments exist
        $deptNames = ['General Ward', 'ICU', 'Pediatrics', 'Maternity', 'Emergency'];
        foreach ($deptNames as $name) {
            \App\Models\NurseDepartment::firstOrCreate(['name' => $name]);
        }

        $deptIndex = 0;
        foreach ($nurseUsers as $user) {
            // Skip if nurse already exists
            if (Nurse::where('email', $user->email)->exists()) {
                $nurses[] = Nurse::where('email', $user->email)->first();
                $deptIndex++;
                continue;
            }

            $dept = \App\Models\NurseDepartment::all()[$deptIndex % \App\Models\NurseDepartment::count()];
            $nurse = Nurse::create([
                'nurse_id' => 'NUR-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'first_name' => explode(' ', $user->name)[1] ?? $user->name,
                'last_name' => explode(' ', $user->name)[2] ?? '',
                'email' => $user->email,
                'phone' => $user->phone ?? $faker->phoneNumber,
                'date_of_birth' => $faker->dateTimeBetween('-40 years', '-25 years')->format('Y-m-d'),
                'gender' => $faker->randomElement(['male', 'female']),
                'nurse_department_id' => $dept->id,
                'qualification' => $faker->randomElement(['RN', 'RM', 'BSN', 'MSN']),
                'address' => $faker->address,
                'joining_date' => $faker->dateTimeBetween('-3 years', 'now')->format('Y-m-d'),
                'salary' => $faker->randomElement([40000, 50000, 60000, 70000]),
                'shift' => $faker->randomElement(['day', 'night', 'rotating']),
            ]);
            $nurses[] = $nurse;
            $deptIndex++;
        }

        $this->command->info('   Created ' . count($nurses) . ' nurse records');
        return $nurses;
    }

    private function seedPatientRecords(array $patientUsers): array
    {
        $faker = Faker::create();
        $patients = [];
        $patientNumber = Patient::count() + 1;

        foreach ($patientUsers as $user) {
            // Skip if patient already exists for this user
            $existing = Patient::where('email', $user->email)->first();
            if ($existing) {
                $patients[] = $existing;
                $patientNumber++;
                continue;
            }

            $gender = $faker->randomElement(['male', 'female']);
            $patient = Patient::create([
                'patient_no' => 'P' . str_pad($patientNumber, 5, '0', STR_PAD_LEFT),
                'first_name' => explode(' ', $user->name)[0],
                'last_name' => explode(' ', $user->name)[1] ?? '',
                'email' => $user->email,
                'phone' => $user->phone ?? $faker->phoneNumber,
                'dob' => $faker->dateTimeBetween('-60 years', '-5 years')->format('Y-m-d'),
                'gender' => $gender,
                'address' => $faker->address,
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ]);
            $patients[] = $patient;
            $patientNumber++;
        }

        // Also create 40 more patients without user accounts
        for ($i = 0; $i < 40; $i++) {
            $gender = $faker->randomElement(['male', 'female']);
            $patientNo = 'P' . str_pad($patientNumber, 5, '0', STR_PAD_LEFT);
            // Skip if patient_no already exists
            if (Patient::where('patient_no', $patientNo)->exists()) {
                $patientNumber++;
                continue;
            }

            $patient = Patient::create([
                'patient_no' => $patientNo,
                'first_name' => $faker->firstName($gender),
                'last_name' => $faker->lastName,
                'email' => $faker->optional(0.7)->email,
                'phone' => $faker->phoneNumber,
                'dob' => $faker->dateTimeBetween('-80 years', '-1 year')->format('Y-m-d'),
                'gender' => $gender,
                'address' => $faker->address,
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ]);
            $patients[] = $patient;
            $patientNumber++;
        }

        $this->command->info('   Created ' . count($patients) . ' patient records');
        return $patients;
    }

    private function seedDepartments(): void
    {
        $departments = ['General Medicine', 'Pediatrics', 'Cardiology', 'Orthopedics', 'Dermatology', 'Radiology', 'Laboratory', 'Pharmacy', 'Emergency', 'ICU', 'Maternity', 'Surgery'];
        foreach ($departments as $dept) {
            EmployeeDepartment::firstOrCreate(['name' => $dept]);
        }

        $designations = ['Consultant', 'Senior Doctor', 'Medical Officer', 'Intern', 'Nurse', 'Senior Nurse', 'Technician', 'Pharmacist', 'Accountant', 'Administrator'];
        foreach ($designations as $desig) {
            Designation::firstOrCreate(['name' => $desig]);
        }

        $this->command->info('   Created ' . count($departments) . ' departments and ' . count($designations) . ' designations');
    }

    private function seedBeds(): void
    {
        $faker = Faker::create();
        $bedTypes = [
            ['name' => 'General Ward', 'description' => 'Standard ward bed', 'charge_per_day' => 2000],
            ['name' => 'Semi-Private', 'description' => 'Semi-private room', 'charge_per_day' => 5000],
            ['name' => 'Private Room', 'description' => 'Private room', 'charge_per_day' => 10000],
            ['name' => 'ICU', 'description' => 'Intensive Care Unit bed', 'charge_per_day' => 25000],
            ['name' => 'HDU', 'description' => 'High Dependency Unit bed', 'charge_per_day' => 15000],
            ['name' => 'Maternity', 'description' => 'Maternity ward bed', 'charge_per_day' => 3000],
        ];

        foreach ($bedTypes as $bt) {
            BedType::firstOrCreate(['name' => $bt['name']], $bt);
        }

        $bedNumber = 1;
        foreach ($bedTypes as $bt) {
            $bedType = BedType::where('name', $bt['name'])->first();
            $count = $bt['name'] === 'ICU' ? 5 : ($bt['name'] === 'HDU' ? 8 : 15);
            for ($i = 1; $i <= $count; $i++) {
                $bedNum = 'BED-' . str_pad($bedNumber, 4, '0', STR_PAD_LEFT);
                if (Bed::where('bed_number', $bedNum)->exists()) {
                    $bedNumber++;
                    continue;
                }
                Bed::create([
                    'bed_number' => $bedNum,
                    'ward_name' => $bt['name'],
                    'bed_type_id' => $bedType->id,
                    'is_available' => $faker->randomElement([true, true, true, false]),
                ]);
                $bedNumber++;
            }
        }

        $this->command->info('   Created ' . count($bedTypes) . ' bed types and ' . ($bedNumber - 1) . ' beds');
    }

    private function seedMedicines(): array
    {
        $faker = Faker::create();
        $medicines = [];

        // Categories
        $categories = ['Antibiotics', 'Analgesics', 'Antihypertensives', 'Antidiabetics', 'Antimalarials', 'Vitamins', 'Antihistamines', 'Gastrointestinal', 'Respiratory', 'Dermatological'];
        foreach ($categories as $cat) {
            MedicineCategory::firstOrCreate(['name' => $cat], ['description' => $cat . ' medications']);
        }

        // Brands
        $brands = ['Generic', 'GSK', 'Pfizer', 'Sanofi', 'AstraZeneca', 'Novartis', 'Bayer', 'Johnson & Johnson'];
        foreach ($brands as $brand) {
            MedicineBrand::firstOrCreate(['name' => $brand]);
        }

        $medicineData = [
            ['name' => 'Amoxicillin 500mg', 'category' => 'Antibiotics', 'price' => 150, 'quantity' => 500, 'form' => 'capsule', 'strength' => '500mg', 'expiry' => '2027-06-15'],
            ['name' => 'Paracetamol 500mg', 'category' => 'Analgesics', 'price' => 50, 'quantity' => 1000, 'form' => 'tablet', 'strength' => '500mg', 'expiry' => '2027-12-31'],
            ['name' => 'Ibuprofen 400mg', 'category' => 'Analgesics', 'price' => 80, 'quantity' => 800, 'form' => 'tablet', 'strength' => '400mg', 'expiry' => '2027-09-20'],
            ['name' => 'Amlodipine 5mg', 'category' => 'Antihypertensives', 'price' => 200, 'quantity' => 300, 'form' => 'tablet', 'strength' => '5mg', 'expiry' => '2027-03-10'],
            ['name' => 'Metformin 500mg', 'category' => 'Antidiabetics', 'price' => 120, 'quantity' => 400, 'form' => 'tablet', 'strength' => '500mg', 'expiry' => '2027-08-25'],
            ['name' => 'Artemether-Lumefantrine', 'category' => 'Antimalarials', 'price' => 300, 'quantity' => 200, 'form' => 'tablet', 'strength' => '20/120mg', 'expiry' => '2027-04-18'],
            ['name' => 'Vitamin C 1000mg', 'category' => 'Vitamins', 'price' => 100, 'quantity' => 600, 'form' => 'tablet', 'strength' => '1000mg', 'expiry' => '2028-01-15'],
            ['name' => 'Cetirizine 10mg', 'category' => 'Antihistamines', 'price' => 70, 'quantity' => 350, 'form' => 'tablet', 'strength' => '10mg', 'expiry' => '2027-11-30'],
            ['name' => 'Omeprazole 20mg', 'category' => 'Gastrointestinal', 'price' => 150, 'quantity' => 250, 'form' => 'capsule', 'strength' => '20mg', 'expiry' => '2027-07-22'],
            ['name' => 'Salbutamol Inhaler', 'category' => 'Respiratory', 'price' => 500, 'quantity' => 100, 'form' => 'inhaler', 'strength' => '100mcg', 'expiry' => '2027-05-14'],
            ['name' => 'Ciprofloxacin 500mg', 'category' => 'Antibiotics', 'price' => 200, 'quantity' => 300, 'form' => 'tablet', 'strength' => '500mg', 'expiry' => '2027-10-08'],
            ['name' => 'Losartan 50mg', 'category' => 'Antihypertensives', 'price' => 180, 'quantity' => 250, 'form' => 'tablet', 'strength' => '50mg', 'expiry' => '2027-02-28'],
            ['name' => 'Glibenclamide 5mg', 'category' => 'Antidiabetics', 'price' => 60, 'quantity' => 400, 'form' => 'tablet', 'strength' => '5mg', 'expiry' => '2027-09-15'],
            ['name' => 'ORS Sachets', 'category' => 'Gastrointestinal', 'price' => 30, 'quantity' => 2000, 'form' => 'sachet', 'strength' => null, 'expiry' => '2028-06-30'],
            ['name' => 'Hydrocortisone Cream', 'category' => 'Dermatological', 'price' => 250, 'quantity' => 150, 'form' => 'cream', 'strength' => '1%', 'expiry' => '2027-08-10'],
        ];

        foreach ($medicineData as $med) {
            $category = MedicineCategory::where('name', $med['category'])->first();
            $medicine = Medicine::create([
                'name' => $med['name'],
                'category_id' => $category->id ?? 1,
                'generic_name' => $med['name'],
                'dosage_form' => $med['form'],
                'strength' => $med['strength'],
                'unit_price' => $med['price'],
                'stock_quantity' => $med['quantity'],
                'minimum_stock' => 50,
                'expiry_date' => $med['expiry'],
                'description' => $med['name'] . ' medication',
            ]);
            $medicines[] = $medicine;
        }

        $this->command->info('   Created ' . count($medicines) . ' medicines');
        return $medicines;
    }

    private function seedLabTests(): array
    {
        $faker = Faker::create();
        $labTests = [];

        $labCategories = ['Hematology', 'Biochemistry', 'Microbiology', 'Urinalysis', 'Serology', 'Histology'];
        foreach ($labCategories as $cat) {
            LabCategory::firstOrCreate(['name' => $cat], ['description' => $cat . ' tests']);
        }

        $testData = [
            ['name' => 'Complete Blood Count (CBC)', 'category' => 'Hematology', 'price' => 1500, 'normal_range' => 'Normal values vary'],
            ['name' => 'Blood Glucose (Fasting)', 'category' => 'Biochemistry', 'price' => 500, 'normal_range' => '70-100 mg/dL'],
            ['name' => 'Lipid Profile', 'category' => 'Biochemistry', 'price' => 2000, 'normal_range' => 'Varies by component'],
            ['name' => 'Liver Function Tests', 'category' => 'Biochemistry', 'price' => 2500, 'normal_range' => 'Varies by component'],
            ['name' => 'Kidney Function Tests', 'category' => 'Biochemistry', 'price' => 2000, 'normal_range' => 'Varies by component'],
            ['name' => 'Malaria Test (RDT)', 'category' => 'Microbiology', 'price' => 500, 'normal_range' => 'Negative'],
            ['name' => 'Urinalysis', 'category' => 'Urinalysis', 'price' => 800, 'normal_range' => 'Normal'],
            ['name' => 'HIV Test', 'category' => 'Serology', 'price' => 1000, 'normal_range' => 'Non-reactive'],
            ['name' => 'Hepatitis B Test', 'category' => 'Serology', 'price' => 1500, 'normal_range' => 'Negative'],
            ['name' => 'Pregnancy Test (hCG)', 'category' => 'Serology', 'price' => 500, 'normal_range' => 'Negative'],
            ['name' => 'Blood Group & Crossmatch', 'category' => 'Hematology', 'price' => 1000, 'normal_range' => 'N/A'],
            ['name' => 'ESR', 'category' => 'Hematology', 'price' => 600, 'normal_range' => '0-20 mm/hr'],
            ['name' => 'HbA1c (Glycated Hemoglobin)', 'category' => 'Biochemistry', 'price' => 3000, 'normal_range' => '< 5.7%'],
            ['name' => 'Thyroid Function (TSH)', 'category' => 'Biochemistry', 'price' => 2500, 'normal_range' => '0.4-4.0 mIU/L'],
            ['name' => 'Stool Analysis', 'category' => 'Microbiology', 'price' => 800, 'normal_range' => 'Normal'],
        ];

        foreach ($testData as $test) {
            $category = LabCategory::where('name', $test['category'])->first();
            $labTest = LabTest::create([
                'test_name' => $test['name'],
                'category_id' => $category->id ?? 1,
                'price' => $test['price'],
                'normal_range' => $test['normal_range'],
                'description' => $test['name'] . ' laboratory test',
            ]);
            $labTests[] = $labTest;
        }

        $this->command->info('   Created ' . count($labTests) . ' lab tests');
        return $labTests;
    }

    private function seedAppointments(array $patients, array $doctors): void
    {
        $faker = Faker::create();
        $statuses = ['pending', 'confirmed', 'completed', 'canceled'];
        $appointmentCount = 0;

        foreach ($patients as $patient) {
            $numAppointments = $faker->numberBetween(1, 3);
            for ($i = 0; $i < $numAppointments; $i++) {
                $doctor = $faker->randomElement($doctors);
                $status = $faker->randomElement($statuses);
                $date = $faker->dateTimeBetween('-1 month', '+1 month');

                $appointment = Appointment::create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $doctor->id,
                    'scheduled_at' => $date->format('Y-m-d H:i:s'),
                    'status' => $status,
                    'note' => $faker->optional(0.5)->sentence,
                ]);
                $appointmentCount++;
            }
        }

        $this->command->info('   Created ' . $appointmentCount . ' appointments');
    }

    private function seedBilling(array $patients): void
    {
        $faker = Faker::create();
        $invoiceCount = 0;

        foreach ($patients as $patient) {
            if ($faker->boolean(60)) {
                $subtotal = $faker->randomFloat(2, 500, 15000);
                $tax = round($subtotal * 0.16, 2);
                $discount = $faker->optional(0.3)->randomFloat(2, 0, $subtotal * 0.2) ?? 0;
                $total = $subtotal + $tax - $discount;
                $status = $faker->randomElement(['pending', 'paid', 'partial', 'overdue']);
                $paidAmount = $status === 'paid' ? $total : ($status === 'partial' ? $total * 0.5 : 0);

                $invoice = Invoice::create([
                    'invoice_number' => 'INV-' . str_pad(rand(10000, 99999), 6, '0', STR_PAD_LEFT),
                    'patient_id' => $patient->id,
                    'invoice_date' => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
                    'due_date' => $faker->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
                    'subtotal' => $subtotal,
                    'tax_amount' => $tax,
                    'discount_amount' => $discount,
                    'total_amount' => $total,
                    'paid_amount' => $paidAmount,
                    'balance_amount' => $total - $paidAmount,
                    'status' => $status,
                    'notes' => $faker->optional(0.5)->sentence,
                ]);

                // Create payment for paid invoices
                if ($paidAmount > 0) {
                    Payment::create([
                        'invoice_id' => $invoice->id,
                        'amount' => $paidAmount,
                        'payment_method' => $faker->randomElement(['cash', 'mpesa', 'card', 'insurance']),
                        'payment_date' => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
                        'reference_number' => 'PAY-' . strtoupper($faker->bothify('??####')),
                    ]);
                }

                $invoiceCount++;
            }
        }

        $this->command->info('   Created ' . $invoiceCount . ' invoices');
    }

    private function seedInsurance(array $patients): void
    {
        $faker = Faker::create();

        // Insurance providers
        $providers = [
            ['name' => 'SHA (Social Health Authority)', 'code' => 'SHA', 'coverage_percentage' => 100, 'is_active' => true],
            ['name' => 'NHIF', 'code' => 'NHIF', 'coverage_percentage' => 80, 'is_active' => true],
            ['name' => 'AAR Insurance', 'code' => 'AAR', 'coverage_percentage' => 75, 'is_active' => true],
            ['name' => 'Jubilee Insurance', 'code' => 'JUB', 'coverage_percentage' => 80, 'is_active' => true],
            ['name' => 'Britam Insurance', 'code' => 'BRI', 'coverage_percentage' => 70, 'is_active' => true],
        ];

        foreach ($providers as $prov) {
            InsuranceProvider::firstOrCreate(['code' => $prov['code']], $prov);
        }

        // Assign insurance to some patients
        $insuredCount = 0;
        foreach ($patients as $patient) {
            if ($faker->boolean(40)) {
                $provider = InsuranceProvider::inRandomOrder()->first();
                PatientInsurance::create([
                    'patient_id' => $patient->id,
                    'insurance_provider_id' => $provider->id,
                    'policy_number' => strtoupper($faker->bothify('??#####')),
                    'member_id' => strtoupper($faker->bothify('??########')),
                    'coverage_percentage' => $provider->coverage_percentage,
                    'start_date' => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                    'end_date' => $faker->dateTimeBetween('+6 months', '+2 years')->format('Y-m-d'),
                    'status' => 'active',
                ]);
                $insuredCount++;
            }
        }

        $this->command->info('   Created ' . count($providers) . ' insurance providers and ' . $insuredCount . ' patient policies');
    }

    private function seedMiscData(): void
    {
        // Blood groups
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        foreach ($bloodGroups as $bg) {
            BloodGroup::firstOrCreate(['name' => $bg]);
        }

        $faker = Faker::create();

        // Blood donors
        for ($i = 0; $i < 10; $i++) {
            BloodDonor::create([
                'name' => $faker->name,
                'phone' => $faker->phoneNumber,
                'blood_group' => $faker->randomElement($bloodGroups),
                'last_donation_date' => $faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
                'status' => 'active',
            ]);
        }

        // Expenses
        $expenseCategories = ['Rent', 'Utilities', 'Salaries', 'Medical Supplies', 'Equipment', 'Maintenance', 'Marketing', 'Insurance'];
        foreach ($expenseCategories as $cat) {
            ExpenseCategory::firstOrCreate(['name' => $cat]);
        }

        for ($i = 0; $i < 20; $i++) {
            $category = ExpenseCategory::inRandomOrder()->first();
            Expense::create([
                'expense_category_id' => $category->id,
                'description' => $faker->sentence,
                'amount' => $faker->randomFloat(2, 5000, 500000),
                'date' => $faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
                'payment_method' => $faker->randomElement(['cash', 'bank_transfer', 'mpesa']),
                'reference' => $faker->optional(0.7)->bothify('EXP-####'),
            ]);
        }

        // Income
        for ($i = 0; $i < 20; $i++) {
            Income::create([
                'description' => $faker->randomElement(['Consultation fee', 'Lab test payment', 'Surgery payment', 'Room charges', 'Pharmacy sales']),
                'amount' => $faker->randomFloat(2, 5000, 200000),
                'date' => $faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
                'category' => $faker->randomElement(['consultation', 'laboratory', 'pharmacy', 'surgery', 'room']),
                'reference' => $faker->optional(0.7)->bothify('INC-####'),
            ]);
        }

        $this->command->info('   Created blood bank, expenses, and income data');
    }

    private function printSummary(): void
    {
        $this->command->info('📊 DATABASE SUMMARY:');
        $this->command->info('   Users:         ' . User::count());
        $this->command->info('   - Admins:      ' . User::role(['Super Admin', 'Hospital Admin'])->count());
        $this->command->info('   - Doctors:     ' . User::role('Doctor')->count());
        $this->command->info('   - Nurses:      ' . User::role('Nurse')->count());
        $this->command->info('   - Staff:       ' . User::role(['Receptionist', 'Pharmacist', 'Lab Technician', 'Accountant', 'HR Officer', 'Inventory Manager'])->count());
        $this->command->info('   - Patients:    ' . User::role('Patient')->count());
        $this->command->info('   Patients:      ' . Patient::count());
        $this->command->info('   Doctors:       ' . Doctor::count());
        $this->command->info('   Nurses:        ' . Nurse::count());
        $this->command->info('   Appointments:  ' . Appointment::count());
        $this->command->info('   OPD Visits:    ' . OpdVisit::count());
        $this->command->info('   Invoices:      ' . Invoice::count());
        $this->command->info('   Medicines:     ' . Medicine::count());
        $this->command->info('   Lab Tests:     ' . LabTest::count());
        $this->command->info('   Beds:          ' . Bed::count());
        $this->command->info('   Insurance:     ' . PatientInsurance::count());
        $this->command->info('');
        $this->command->info('🔑 LOGIN CREDENTIALS (all passwords below):');
        $this->command->info('');
        $this->command->info('   Admin:         admin@duncohms.com / admin123');
        $this->command->info('   Hospital Admin: hospital@duncohms.com / admin123');
        $this->command->info('   Doctor:        dr.mwangi@duncohms.com / doctor123');
        $this->command->info('   Nurse:         nurse.njeri@duncohms.com / nurse123');
        $this->command->info('   Receptionist:  reception.kimani@duncohms.com / reception123');
        $this->command->info('   Pharmacist:    pharmacist.otieno@duncohms.com / pharmacist123');
        $this->command->info('   Lab Tech:      lab.wekesa@duncohms.com / labtech123');
        $this->command->info('   Accountant:    accountant.ogutu@duncohms.com / accountant123');
        $this->command->info('   HR Officer:    hr@duncohms.com / hr123');
        $this->command->info('   Patient:       patient.kamau@duncohms.com / patient123');
        $this->command->info('');
    }
}
