<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Barryvdh\DomPDF\Facade\Pdf;

$themeSettings = [
    'primary_color' => \App\Models\SystemSetting::get('primary_color', '#10b981'),
    'hospital_logo' => \App\Models\SystemSetting::get('hospital_logo', ''),
    'hospital_name' => \App\Models\SystemSetting::get('hospital_name', config('app.name', 'DuncoHMS')),
    'hospital_address' => \App\Models\SystemSetting::get('hospital_address', ''),
    'hospital_phone' => \App\Models\SystemSetting::get('hospital_phone', ''),
    'hospital_email' => \App\Models\SystemSetting::get('hospital_email', ''),
];
$reportData = [
    'generated_at' => now()->format('F d, Y \a\t H:i'),
    'system_name' => \App\Models\SystemSetting::get('hospital_name', config('app.name')),
    'system_url' => config('app.url'),
];

// Generate sample documents
$samples = [];

// 1. Invoice PDF
$invoice = \App\Models\Invoice::with(['patient', 'items', 'payments'])->first();
if ($invoice) {
    $pdf = Pdf::loadView('hms.billing.invoices.pdf', compact('invoice', 'themeSettings'));
    $pdf->save(storage_path('app/public/samples/invoice-sample.pdf'));
    $samples[] = 'Invoice PDF';
}

// 2. Patient Report PDF
$patients = \App\Models\Patient::latest()->limit(10)->get();
$pdf = Pdf::loadView('hms.reports.patients-pdf', compact('patients', 'themeSettings'));
$pdf->save(storage_path('app/public/samples/patient-report-sample.pdf'));
$samples[] = 'Patient Report PDF';

// 3. Revenue Report PDF
$payments = \App\Models\Payment::with('invoice.patient')->latest()->limit(10)->get();
$pdf = Pdf::loadView('hms.reports.revenue-pdf', compact('payments', 'themeSettings'));
$pdf->save(storage_path('app/public/samples/revenue-report-sample.pdf'));
$samples[] = 'Revenue Report PDF';

// 4. Employee List PDF
$employees = \App\Models\Employee::with('department')->latest()->limit(10)->get();
$pdf = Pdf::loadView('hms.hr.reports.employee-list-pdf', compact('employees', 'themeSettings'));
$pdf->save(storage_path('app/public/samples/employee-list-sample.pdf'));
$samples[] = 'Employee List PDF';

// 5. Patient ID Card
$patient = \App\Models\Patient::first();
if ($patient) {
    $base64Barcode = '';
    $base64Qr = '';
    if (class_exists('Picqer\Barcode\BarcodeGeneratorPNG')) {
        $barcode = \Picqer\Barcode\BarcodeGeneratorPNG::generate($patient->patient_no ?? $patient->id);
        $base64Barcode = base64_encode($barcode);
        $qr = \Picqer\Barcode\BarcodeGeneratorPNG::generate($patient->patient_no ?? $patient->id, \Picqer\Barcode\BarcodeGeneratorPNG::TYPE_QRCODE);
        $base64Qr = base64_encode($qr);
    }
    $pdf = Pdf::loadView('hms.id-cards.patient-card', compact('patient', 'themeSettings', 'base64Barcode', 'base64Qr'));
    $pdf->save(storage_path('app/public/samples/patient-id-card-sample.pdf'));
    $samples[] = 'Patient ID Card';
}

// 6. Employee ID Card
$employee = \App\Models\Employee::first();
if ($employee) {
    $base64Barcode = '';
    $base64Qr = '';
    $photo = null;
    if (class_exists('Picqer\Barcode\BarcodeGeneratorPNG')) {
        $barcode = \Picqer\Barcode\BarcodeGeneratorPNG::generate($employee->employee_id ?? $employee->id);
        $base64Barcode = base64_encode($barcode);
        $qr = \Picqer\Barcode\BarcodeGeneratorPNG::generate($employee->employee_id ?? $employee->id, \Picqer\Barcode\BarcodeGeneratorPNG::TYPE_QRCODE);
        $base64Qr = base64_encode($qr);
    }
    $pdf = Pdf::loadView('hms.id-cards.employee-card', compact('employee', 'themeSettings', 'base64Barcode', 'base64Qr', 'photo'));
    $pdf->save(storage_path('app/public/samples/employee-id-card-sample.pdf'));
    $samples[] = 'Employee ID Card';
}

// 7. Prescription PDF
$prescription = \App\Models\Prescription::with(['patient', 'doctor', 'items.medicine'])->first();
if ($prescription) {
    $pdf = Pdf::loadView('hms.prescriptions.e-prescription.pdf', compact('prescription', 'themeSettings'));
    $pdf->save(storage_path('app/public/samples/prescription-sample.pdf'));
    $samples[] = 'Prescription PDF';
}

// 8. Stock Take Sheet
$stockItems = \App\Models\StoreStock::with(['medicine', 'store'])->limit(15)->get();
$pdf = Pdf::loadView('hms.inventory.stock-take', compact('stockItems', 'themeSettings'));
$pdf->save(storage_path('app/public/samples/stock-take-sample.pdf'));
$samples[] = 'Stock Take Sheet';

echo "=== SAMPLE DOCUMENTS GENERATED ===\n";
foreach ($samples as $i => $name) {
    $file = storage_path('app/public/samples/' . strtolower(str_replace(' ', '-', $name)) . '.pdf');
    $size = file_exists($file) ? filesize($file) : 0;
    echo ($i+1) . ". {$name} ({$size} bytes)\n";
}
echo "\nTotal: " . count($samples) . " sample documents\n";
echo "Location: storage/app/public/samples/\n";
