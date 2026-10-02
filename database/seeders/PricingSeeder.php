<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Package;
use App\Models\PackageItem;
use App\Models\PriceList;
use App\Models\PriceItem;

class PricingSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Services Catalog
        $services = [
            // Consultation
            ['name' => 'General Consultation', 'code' => 'CON-0001', 'category' => 'consultation', 'default_price' => 1500, 'description' => 'General doctor consultation'],
            ['name' => 'Specialist Consultation', 'code' => 'CON-0002', 'category' => 'consultation', 'default_price' => 2500, 'description' => 'Specialist consultation'],
            ['name' => 'Follow-up Visit', 'code' => 'CON-0003', 'category' => 'consultation', 'default_price' => 800, 'description' => 'Follow-up visit'],
            ['name' => 'Emergency Consultation', 'code' => 'CON-0004', 'category' => 'consultation', 'default_price' => 3000, 'description' => 'Emergency consultation'],
            ['name' => 'Home Visit', 'code' => 'CON-0005', 'category' => 'consultation', 'default_price' => 5000, 'description' => 'Doctor home visit'],

            // Lab
            ['name' => 'Full Blood Count', 'code' => 'LAB-0001', 'category' => 'lab', 'default_price' => 800, 'description' => 'FBC test'],
            ['name' => 'Malaria Parasites', 'code' => 'LAB-0002', 'category' => 'lab', 'default_price' => 500, 'description' => 'Malaria test'],
            ['name' => 'Liver Function Test', 'code' => 'LAB-0003', 'category' => 'lab', 'default_price' => 1200, 'description' => 'LFT panel'],
            ['name' => 'Kidney Function Test', 'code' => 'LAB-0004', 'category' => 'lab', 'default_price' => 1200, 'description' => 'KFT panel'],
            ['name' => 'Lipid Profile', 'code' => 'LAB-0005', 'category' => 'lab', 'default_price' => 1500, 'description' => 'Lipid profile'],
            ['name' => 'Blood Glucose', 'code' => 'LAB-0006', 'category' => 'lab', 'default_price' => 300, 'description' => 'Blood sugar test'],
            ['name' => 'Urinalysis', 'code' => 'LAB-0007', 'category' => 'lab', 'default_price' => 400, 'description' => 'Urine test'],
            ['name' => 'HIV Test', 'code' => 'LAB-0008', 'category' => 'lab', 'default_price' => 500, 'description' => 'HIV screening'],
            ['name' => 'Pregnancy Test', 'code' => 'LAB-0009', 'category' => 'lab', 'default_price' => 300, 'description' => 'hCG test'],
            ['name' => 'Stool Test', 'code' => 'LAB-0010', 'category' => 'lab', 'default_price' => 400, 'description' => 'Stool analysis'],

            // Radiology
            ['name' => 'Chest X-Ray', 'code' => 'RAD-0001', 'category' => 'radiology', 'default_price' => 2000, 'description' => 'Chest X-ray PA view'],
            ['name' => 'Abdominal X-Ray', 'code' => 'RAD-0002', 'category' => 'radiology', 'default_price' => 2000, 'description' => 'Abdominal X-ray'],
            ['name' => 'Ultrasound Abdomen', 'code' => 'RAD-0003', 'category' => 'radiology', 'default_price' => 3500, 'description' => 'Abdominal ultrasound'],
            ['name' => 'Ultrasound Pelvis', 'code' => 'RAD-0004', 'category' => 'radiology', 'default_price' => 3500, 'description' => 'Pelvic ultrasound'],
            ['name' => 'CT Scan', 'code' => 'RAD-0005', 'category' => 'radiology', 'default_price' => 8000, 'description' => 'CT scan'],
            ['name' => 'MRI', 'code' => 'RAD-0006', 'category' => 'radiology', 'default_price' => 15000, 'description' => 'MRI scan'],

            // Bed
            ['name' => 'General Ward Bed', 'code' => 'BED-0001', 'category' => 'bed', 'default_price' => 2000, 'description' => 'General ward per day'],
            ['name' => 'Private Room', 'code' => 'BED-0002', 'category' => 'bed', 'default_price' => 5000, 'description' => 'Private room per day'],
            ['name' => 'ICU Bed', 'code' => 'BED-0003', 'category' => 'bed', 'default_price' => 10000, 'description' => 'ICU per day'],
            ['name' => 'HDU Bed', 'code' => 'BED-0004', 'category' => 'bed', 'default_price' => 7000, 'description' => 'HDU per day'],
            ['name' => 'Maternity Bed', 'code' => 'BED-0005', 'category' => 'bed', 'default_price' => 3000, 'description' => 'Maternity per day'],

            // Meal
            ['name' => 'Standard Meal', 'code' => 'MEA-0001', 'category' => 'meal', 'default_price' => 500, 'description' => 'Standard patient meal'],
            ['name' => 'Diabetic Meal', 'code' => 'MEA-0002', 'category' => 'meal', 'default_price' => 700, 'description' => 'Diabetic-friendly meal'],
            ['name' => 'Soft Diet', 'code' => 'MEA-0003', 'category' => 'meal', 'default_price' => 600, 'description' => 'Soft diet meal'],
            ['name' => 'Maternity Meal', 'code' => 'MEA-0004', 'category' => 'meal', 'default_price' => 800, 'description' => 'Maternity/postnatal meal'],

            // Procedure
            ['name' => 'Minor Procedure', 'code' => 'PRC-0001', 'category' => 'procedure', 'default_price' => 3000, 'description' => 'Minor surgical procedure'],
            ['name' => 'Major Procedure', 'code' => 'PRC-0002', 'category' => 'procedure', 'default_price' => 15000, 'description' => 'Major surgical procedure'],
            ['name' => 'Normal Delivery', 'code' => 'PRC-0003', 'category' => 'procedure', 'default_price' => 12000, 'description' => 'Normal vaginal delivery'],
            ['name' => 'Caesarean Section', 'code' => 'PRC-0004', 'category' => 'procedure', 'default_price' => 30000, 'description' => 'C-section delivery'],
            ['name' => 'Dilation & Curettage', 'code' => 'PRC-0005', 'category' => 'procedure', 'default_price' => 8000, 'description' => 'D&C procedure'],

            // Registration
            ['name' => 'Patient Registration', 'code' => 'REG-0001', 'category' => 'other', 'default_price' => 50, 'description' => 'New patient registration fee'],
            ['name' => 'Patient Card', 'code' => 'REG-0002', 'category' => 'other', 'default_price' => 100, 'description' => 'Patient ID card printing'],
        ];

        foreach ($services as $s) {
            Service::firstOrCreate(['code' => $s['code']], $s + ['currency' => 'KES', 'is_active' => true, 'is_taxable' => false]);
        }

        // 2. Seed Default Price List
        $priceList = PriceList::firstOrCreate(
            ['name' => 'Standard Price List'],
            [
                'description' => 'Default pricing for all services',
                'currency' => 'KES',
                'effective_from' => now()->subYear(),
                'is_default' => true,
                'is_active' => true,
            ]
        );

        // Add all services to price list at default price
        $allServices = Service::all();
        foreach ($allServices as $service) {
            PriceItem::firstOrCreate(
                ['price_list_id' => $priceList->id, 'service_id' => $service->id],
                ['price' => $service->default_price, 'currency' => 'KES']
            );
        }

        // 3. Seed Predefined Packages
        $packages = [
            [
                'name' => 'Basic Admission Pack',
                'description' => 'General ward admission with basic services for 3 days',
                'price' => 15000,
                'duration_days' => 3,
                'inclusions' => 'General ward bed (3 days), 3 consultations, 1 FBC, 1 malaria test, meals (3 days)',
                'terms_conditions' => 'Additional services billed separately',
                'is_active' => true,
                'items' => [
                    ['item_type' => 'bed', 'item_name' => 'General Ward Bed', 'quantity' => 3, 'unit_price' => 2000, 'total_price' => 6000],
                    ['item_type' => 'consultation', 'item_name' => 'General Consultation', 'quantity' => 3, 'unit_price' => 1500, 'total_price' => 4500],
                    ['item_type' => 'lab_test', 'item_name' => 'Full Blood Count', 'quantity' => 1, 'unit_price' => 800, 'total_price' => 800],
                    ['item_type' => 'lab_test', 'item_name' => 'Malaria Parasites', 'quantity' => 1, 'unit_price' => 500, 'total_price' => 500],
                    ['item_type' => 'meal', 'item_name' => 'Standard Meal', 'quantity' => 3, 'unit_price' => 500, 'total_price' => 1500],
                    ['item_type' => 'other', 'item_name' => 'Registration Fee', 'quantity' => 1, 'unit_price' => 50, 'total_price' => 50],
                    ['item_type' => 'other', 'item_name' => 'Patient Card', 'quantity' => 1, 'unit_price' => 100, 'total_price' => 100],
                    ['item_type' => 'procedure', 'item_name' => 'Minor Procedure', 'quantity' => 1, 'unit_price' => 1550, 'total_price' => 1550],
                ],
            ],
            [
                'name' => 'Maternity Pack',
                'description' => 'Normal delivery package with antenatal care',
                'price' => 25000,
                'duration_days' => 5,
                'inclusions' => 'Normal delivery, 3 days maternity bed, 5 meals, newborn assessment, postnatal check',
                'terms_conditions' => 'Caesarean section not included. Additional services billed separately.',
                'is_active' => true,
                'items' => [
                    ['item_type' => 'procedure', 'item_name' => 'Normal Delivery', 'quantity' => 1, 'unit_price' => 12000, 'total_price' => 12000],
                    ['item_type' => 'bed', 'item_name' => 'Maternity Bed', 'quantity' => 3, 'unit_price' => 3000, 'total_price' => 9000],
                    ['item_type' => 'meal', 'item_name' => 'Maternity Meal', 'quantity' => 5, 'unit_price' => 800, 'total_price' => 4000],
                    ['item_type' => 'consultation', 'item_name' => 'Postnatal Check', 'quantity' => 1, 'unit_price' => 1500, 'total_price' => 1500],
                    ['item_type' => 'other', 'item_name' => 'Registration Fee', 'quantity' => 1, 'unit_price' => 50, 'total_price' => 50],
                    ['item_type' => 'other', 'item_name' => 'Patient Card', 'quantity' => 1, 'unit_price' => 100, 'total_price' => 100],
                    ['item_type' => 'other', 'item_name' => 'Newborn Assessment', 'quantity' => 1, 'unit_price' => 2000, 'total_price' => 2000],
                    ['item_type' => 'other', 'item_name' => 'Miscellaneous', 'quantity' => 1, 'unit_price' => 350, 'total_price' => 350],
                ],
            ],
            [
                'name' => 'Antenatal Care Pack',
                'description' => 'Comprehensive antenatal visits package',
                'price' => 8000,
                'duration_days' => 90,
                'inclusions' => '5 antenatal visits, 2 ultrasounds, 3 lab tests, supplements',
                'terms_conditions' => 'For low-risk pregnancies. Delivery not included.',
                'is_active' => true,
                'items' => [
                    ['item_type' => 'consultation', 'item_name' => 'Antenatal Visit', 'quantity' => 5, 'unit_price' => 800, 'total_price' => 4000],
                    ['item_type' => 'radiology', 'item_name' => 'Ultrasound Pelvis', 'quantity' => 2, 'unit_price' => 3500, 'total_price' => 7000],
                    ['item_type' => 'lab_test', 'item_name' => 'Full Blood Count', 'quantity' => 1, 'unit_price' => 800, 'total_price' => 800],
                    ['item_type' => 'lab_test', 'item_name' => 'Blood Glucose', 'quantity' => 1, 'unit_price' => 300, 'total_price' => 300],
                    ['item_type' => 'lab_test', 'item_name' => 'Urinalysis', 'quantity' => 1, 'unit_price' => 400, 'total_price' => 400],
                ],
            ],
        ];

        foreach ($packages as $pkg) {
            $items = $pkg['items'];
            unset($pkg['items']);
            $package = Package::firstOrCreate(['name' => $pkg['name']], $pkg);
            foreach ($items as $item) {
                PackageItem::firstOrCreate(
                    ['package_id' => $package->id, 'item_name' => $item['item_name']],
                    $item
                );
            }
        }

        $this->command->info('Pricing seeded: ' . Service::count() . ' services, ' . Package::count() . ' packages, ' . PriceList::count() . ' price list');
    }
}
