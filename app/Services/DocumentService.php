<?php

namespace App\Services;

use App\Models\SystemSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class DocumentService
{
    /**
     * Get hospital branding settings
     */
    public static function branding(): array
    {
        $logo = SystemSetting::get('hospital_logo', '');
        $logoSrc = '';
        if ($logo) {
            if (str_starts_with($logo, 'http') || str_starts_with($logo, 'data:')) {
                $logoSrc = $logo;
            } elseif (str_starts_with($logo, '/storage/')) {
                $logoSrc = asset($logo);
            } else {
                $logoSrc = asset('storage/' . $logo);
            }
        }

        return [
            'hospital_name' => SystemSetting::get('hospital_name', config('app.name', 'Dunco HMS')),
            'hospital_short_name' => SystemSetting::get('hospital_short_name', ''),
            'hospital_address' => SystemSetting::get('hospital_address', ''),
            'hospital_phone' => SystemSetting::get('hospital_phone', ''),
            'hospital_email' => SystemSetting::get('hospital_email', ''),
            'hospital_website' => SystemSetting::get('hospital_website', ''),
            'primary_color' => SystemSetting::get('primary_color', '#000075'),
            'secondary_color' => SystemSetting::get('secondary_color', '#00001A'),
            'logo' => $logo,
            'logo_src' => $logoSrc,
            'currency_symbol' => SystemSetting::get('currency_symbol', 'KES'),
            'document_footer' => SystemSetting::get('document_footer', ''),
            'tax_name' => SystemSetting::get('tax_name', 'VAT'),
            'tax_rate' => SystemSetting::get('tax_rate', 0),
        ];
    }

    /**
     * Generate a branded PDF
     */
    public static function generatePdf(string $view, array $data = [], string $filename = 'document', string $orientation = 'portrait')
    {
        $branding = self::branding();
        $data['branding'] = $branding;

        $pdf = Pdf::loadView($view, $data)
            ->setPaper($orientation == 'landscape' ? 'A4' : 'A4', $orientation)
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => true,
            ]);

        return $pdf->download($filename . '-' . now()->format('Y-m-d-His') . '.pdf');
    }

    /**
     * Generate a branded PDF as string (for attaching to emails)
     */
    public static function generatePdfString(string $view, array $data = []): string
    {
        $branding = self::branding();
        $data['branding'] = $branding;

        $pdf = Pdf::loadView($view, $data)
            ->setPaper('A4', 'portrait')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => true,
            ]);

        return $pdf->output();
    }

    /**
     * Generate thermal receipt PDF (80mm width)
     */
    public static function generateThermalPdf(string $view, array $data = [], string $filename = 'receipt')
    {
        $branding = self::branding();
        $data['branding'] = $branding;

        $pdf = Pdf::loadView($view, $data)
            ->setPaper([80, 200], 'portrait')  // 80mm width thermal paper
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
            ]);

        return $pdf->download($filename . '-' . now()->format('Y-m-d-His') . '.pdf');
    }

    /**
     * Generate ID card PDF (85.6mm x 54mm - CR80 card size)
     */
    public static function generateIdCardPdf(string $view, array $data = [], string $filename = 'id-card')
    {
        $branding = self::branding();
        $data['branding'] = $branding;

        $pdf = Pdf::loadView($view, $data)
            ->setPaper([85.6, 54], 'portrait')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
            ]);

        return $pdf->download($filename . '-' . now()->format('Y-m-d-His') . '.pdf');
    }

    /**
     * Generate certificate PDF (A4 landscape)
     */
    public static function generateCertificatePdf(string $view, array $data = [], string $filename = 'certificate')
    {
        $branding = self::branding();
        $data['branding'] = $branding;

        $pdf = Pdf::loadView($view, $data)
            ->setPaper('A4', 'landscape')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        return $pdf->download($filename . '-' . now()->format('Y-m-d-His') . '.pdf');
    }

    /**
     * Get available document types
     */
    public static function documentTypes(): array
    {
        return [
            // Patient & Registration
            'patient-registration' => 'Patient Registration Form',
            'patient-profile' => 'Patient Profile',
            'patient-id-card' => 'Patient ID Card',
            'patient-statement' => 'Patient Account Statement',
            'patient-referral' => 'Patient Referral Letter',
            'consent-form' => 'Consent Form',
            'discharge-summary' => 'Discharge Summary',
            'medical-certificate' => 'Medical Certificate',
            'sick-leave' => 'Sick Leave Certificate',
            'appointment-slip' => 'Appointment Slip',
            'visit-token' => 'Queue/Visit Token',

            // Clinical
            'consultation-notes' => 'Consultation Notes',
            'prescription' => 'Prescription',
            'lab-request' => 'Lab Request Form',
            'lab-report' => 'Lab Report',
            'radiology-request' => 'Radiology Request',
            'radiology-report' => 'Radiology Report',
            'vitals-chart' => 'Vital Signs Chart',
            'nursing-notes' => 'Nursing Notes',

            // Pharmacy
            'dispensing-receipt' => 'Pharmacy Dispensing Receipt',
            'purchase-order' => 'Purchase Order',
            'stock-receipt' => 'Stock Receipt',
            'expiry-report' => 'Expiry Report',

            // Billing
            'invoice' => 'Invoice',
            'payment-receipt' => 'Payment Receipt',
            'thermal-receipt' => 'Thermal Receipt',
            'patient-bill' => 'Patient Bill',
            'outstanding-balance' => 'Outstanding Balance Report',

            // Insurance
            'insurance-claim' => 'Insurance Claim',
            'claim-submission' => 'Claim Submission',

            // HR
            'employee-id-card' => 'Employee ID Card',
            'employee-profile' => 'Employee Profile',
            'payslip' => 'Payslip',
            'leave-application' => 'Leave Application',
            'attendance-report' => 'Attendance Report',
            'shift-roster' => 'Shift Roster',
            'training-certificate' => 'Training Certificate',

            // Reports
            'patient-report' => 'Patient Report',
            'revenue-report' => 'Revenue Report',
            'expense-report' => 'Expense Report',
            'appointment-report' => 'Appointment Report',
            'bed-occupancy' => 'Bed Occupancy Report',
            'moh-opd-summary' => 'MOH OPD Summary',
            'moh-ipd-summary' => 'MOH IPD Summary',
            'moh-disease-surveillance' => 'MOH Disease Surveillance',
            'moh-maternal-health' => 'MOH Maternal Health',
            'moh-pharmacy-consumption' => 'MOH Pharmacy Consumption',
            'moh-revenue-collection' => 'MOH Revenue Collection',
            'moh-staff-attendance' => 'MOH Staff Attendance',
            'moh-bed-occupancy' => 'MOH Bed Occupancy',

            // Birth & Death
            'birth-certificate' => 'Birth Certificate',
            'death-certificate' => 'Death Certificate',

            // Inventory
            'goods-received-note' => 'Goods Received Note',
            'stock-adjustment' => 'Stock Adjustment',
            'stock-transfer' => 'Stock Transfer',
        ];
    }
}
