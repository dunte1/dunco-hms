<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\OpdVisit;
use App\Models\IpdAdmission;
use App\Models\Patient;
use App\Models\BirthReport;
use App\Models\DeathReport;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\InsuranceClaim;
use App\Models\PatientDiagnosis;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class MohReportsController extends Controller
{
    public function index(): View
    {
        return view('hms.reports.moh-dashboard');
    }

    public function opdSummary(Request $request): View
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
        ]);

        $query = OpdVisit::with(['patient', 'doctor']);

        if ($request->filled('date_from')) {
            $query->whereDate('visit_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('visit_date', '<=', $request->date_to);
        }

        $visits = $query->latest('visit_date')->paginate(20);

        $totalVisits = (clone $query)->count();

        $byType = (clone $query)
            ->selectRaw('visit_type, COUNT(*) as count')
            ->groupBy('visit_type')
            ->get();

        $byDepartment = (clone $query)
            ->join('doctor_departments', 'opd_visits.doctor_id', '=', 'doctor_departments.id')
            ->selectRaw('doctor_departments.name as department_name, COUNT(*) as count')
            ->groupBy('doctor_departments.name')
            ->orderByDesc('count')
            ->get();

        $topDiagnoses = PatientDiagnosis::selectRaw('diagnosis, COUNT(*) as count')
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('diagnosis_date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('diagnosis_date', '<=', $request->date_to))
            ->groupBy('diagnosis')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $totalRevenue = (clone $query)->sum('consultation_fee');

        return view('hms.reports.moh-opd-summary', compact(
            'visits', 'totalVisits', 'byType', 'byDepartment', 'topDiagnoses', 'totalRevenue'
        ));
    }

    public function ipdSummary(Request $request): View
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
        ]);

        $query = IpdAdmission::with(['patient', 'doctor', 'ward']);

        if ($request->filled('date_from')) {
            $query->whereDate('admission_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('admission_date', '<=', $request->date_to);
        }

        $admissions = $query->latest('admission_date')->paginate(20);

        $totalAdmissions = (clone $query)->count();
        $totalDischarges = (clone $query)->whereNotNull('discharge_date')->count();
        $averageLengthOfStay = (clone $query)
            ->whereNotNull('discharge_date')
            ->selectRaw('AVG(DATEDIFF(discharge_date, admission_date)) as avg_los')
            ->value('avg_los') ?? 0;

        $bedOccupancy = IpdAdmission::whereNull('discharge_date')->count();
        $totalBeds = \App\Models\Bed::count();
        $occupancyRate = $totalBeds > 0 ? round(($bedOccupancy / $totalBeds) * 100, 1) : 0;

        $topDiagnoses = (clone $query)
            ->selectRaw('diagnosis, COUNT(*) as count')
            ->whereNotNull('diagnosis')
            ->groupBy('diagnosis')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return view('hms.reports.moh-ipd-summary', compact(
            'admissions', 'totalAdmissions', 'totalDischarges', 'averageLengthOfStay',
            'bedOccupancy', 'totalBeds', 'occupancyRate', 'topDiagnoses'
        ));
    }

    public function diseaseSurveillance(Request $request): View
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
        ]);

        $diseases = PatientDiagnosis::query()
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('diagnosis_date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('diagnosis_date', '<=', $request->date_to))
            ->selectRaw('diagnosis, COUNT(*) as count')
            ->groupBy('diagnosis')
            ->orderByDesc('count')
            ->get();

        $trend = PatientDiagnosis::query()
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('diagnosis_date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('diagnosis_date', '<=', $request->date_to))
            ->selectRaw('DATE_FORMAT(diagnosis_date, "%Y-%m") as month, diagnosis, COUNT(*) as count')
            ->groupBy('month', 'diagnosis')
            ->orderBy('month')
            ->get();

        return view('hms.reports.moh-disease-surveillance', compact('diseases', 'trend'));
    }

    public function maternalHealth(Request $request): View
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
        ]);

        $query = BirthReport::query();

        if ($request->filled('date_from')) {
            $query->whereDate('birth_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('birth_date', '<=', $request->date_to);
        }

        $births = $query->latest('birth_date')->paginate(20);

        $totalBirths = (clone $query)->count();
        $byDeliveryType = (clone $query)
            ->selectRaw('delivery_type, COUNT(*) as count')
            ->groupBy('delivery_type')
            ->get();
        $withComplications = (clone $query)->whereNotNull('complications')->count();
        $maternalDeaths = DeathReport::query()
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('death_date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('death_date', '<=', $request->date_to))
            ->count();

        return view('hms.reports.moh-maternal-health', compact(
            'births', 'totalBirths', 'byDeliveryType', 'withComplications', 'maternalDeaths'
        ));
    }

    public function pharmacyConsumption(Request $request): View
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
        ]);

        $prescriptions = Prescription::with('items.medicine')
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('prescription_date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('prescription_date', '<=', $request->date_to))
            ->latest('prescription_date')
            ->paginate(20);

        $totalPrescriptions = Prescription::query()
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('prescription_date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('prescription_date', '<=', $request->date_to))
            ->count();

        $totalMedicinesDispensed = PrescriptionItem::query()
            ->whereHas('prescription', function ($q) use ($request) {
                $q->when($request->filled('date_from'), fn($q) => $q->whereDate('prescription_date', '>=', $request->date_from));
                $q->when($request->filled('date_to'), fn($q) => $q->whereDate('prescription_date', '<=', $request->date_to));
            })
            ->sum('quantity');

        $topMedicines = PrescriptionItem::with('medicine')
            ->whereHas('prescription', function ($q) use ($request) {
                $q->when($request->filled('date_from'), fn($q) => $q->whereDate('prescription_date', '>=', $request->date_from));
                $q->when($request->filled('date_to'), fn($q) => $q->whereDate('prescription_date', '<=', $request->date_to));
            })
            ->selectRaw('medicine_id, SUM(quantity) as total_qty')
            ->groupBy('medicine_id')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        $stockValue = Medicine::sum('stock_quantity');
        $expiringMedicines = MedicineBatch::where('expiry_date', '<=', now()->addDays(30))
            ->where('expiry_date', '>=', now())
            ->count();

        return view('hms.reports.moh-pharmacy-consumption', compact(
            'prescriptions', 'totalPrescriptions', 'totalMedicinesDispensed',
            'topMedicines', 'stockValue', 'expiringMedicines'
        ));
    }

    public function revenueCollection(Request $request): View
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
        ]);

        $paymentsQuery = Payment::query();

        if ($request->filled('date_from')) {
            $paymentsQuery->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $paymentsQuery->whereDate('payment_date', '<=', $request->date_to);
        }

        $payments = (clone $paymentsQuery)->latest('payment_date')->paginate(20);

        $totalCollected = (clone $paymentsQuery)->sum('amount');
        $byMethod = (clone $paymentsQuery)
            ->selectRaw('payment_method, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get();

        $outstandingBalance = Invoice::query()
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('invoice_date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('invoice_date', '<=', $request->date_to))
            ->sum('balance_amount');

        $byDepartment = Invoice::query()
            ->join('opd_visits', 'invoices.patient_id', '=', 'opd_visits.patient_id')
            ->join('doctor_departments', 'opd_visits.doctor_id', '=', 'doctor_departments.id')
            ->selectRaw('doctor_departments.name as department_name, SUM(invoices.total_amount) as total')
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('invoices.invoice_date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('invoices.invoice_date', '<=', $request->date_to))
            ->groupBy('doctor_departments.name')
            ->orderByDesc('total')
            ->get();

        return view('hms.reports.moh-revenue-collection', compact(
            'payments', 'totalCollected', 'byMethod', 'outstandingBalance', 'byDepartment'
        ));
    }

    public function generatePdf(string $type, Request $request)
    {
        $viewMap = [
            'opd-summary' => 'hms.reports.moh-opd-summary-pdf',
            'ipd-summary' => 'hms.reports.moh-ipd-summary-pdf',
            'disease-surveillance' => 'hms.reports.moh-disease-surveillance-pdf',
            'maternal-health' => 'hms.reports.moh-maternal-health-pdf',
            'pharmacy-consumption' => 'hms.reports.moh-pharmacy-consumption-pdf',
            'revenue-collection' => 'hms.reports.moh-revenue-collection-pdf',
        ];

        if (!isset($viewMap[$type])) {
            return back()->withErrors(['error' => 'Invalid report type.']);
        }

        $data = match ($type) {
            'opd-summary' => $this->getOpdSummaryData($request),
            'ipd-summary' => $this->getIpdSummaryData($request),
            'disease-surveillance' => $this->getDiseaseSurveillanceData($request),
            'maternal-health' => $this->getMaternalHealthData($request),
            'pharmacy-consumption' => $this->getPharmacyConsumptionData($request),
            'revenue-collection' => $this->getRevenueCollectionData($request),
        };

        $pdf = Pdf::loadView($viewMap[$type], $data);
        $filename = "moh-{$type}-report-" . now()->format('Y-m-d') . ".pdf";

        return $pdf->download($filename);
    }

    private function getOpdSummaryData(Request $request): array
    {
        $query = OpdVisit::query();
        if ($request->filled('date_from')) {
            $query->whereDate('visit_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('visit_date', '<=', $request->date_to);
        }

        return [
            'totalVisits' => (clone $query)->count(),
            'byType' => (clone $query)->selectRaw('visit_type, COUNT(*) as count')->groupBy('visit_type')->get(),
            'topDiagnoses' => PatientDiagnosis::selectRaw('diagnosis, COUNT(*) as count')
                ->when($request->filled('date_from'), fn($q) => $q->whereDate('diagnosis_date', '>=', $request->date_from))
                ->when($request->filled('date_to'), fn($q) => $q->whereDate('diagnosis_date', '<=', $request->date_to))
                ->groupBy('diagnosis')->orderByDesc('count')->limit(10)->get(),
            'totalRevenue' => (clone $query)->sum('consultation_fee'),
            'dateFrom' => $request->date_from,
            'dateTo' => $request->date_to,
        ];
    }

    private function getIpdSummaryData(Request $request): array
    {
        $query = IpdAdmission::query();
        if ($request->filled('date_from')) {
            $query->whereDate('admission_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('admission_date', '<=', $request->date_to);
        }

        return [
            'totalAdmissions' => (clone $query)->count(),
            'totalDischarges' => (clone $query)->whereNotNull('discharge_date')->count(),
            'averageLengthOfStay' => (clone $query)->whereNotNull('discharge_date')
                ->selectRaw('AVG(DATEDIFF(discharge_date, admission_date))')->value('avg_los') ?? 0,
            'topDiagnoses' => (clone $query)->selectRaw('diagnosis, COUNT(*) as count')
                ->whereNotNull('diagnosis')->groupBy('diagnosis')->orderByDesc('count')->limit(10)->get(),
            'dateFrom' => $request->date_from,
            'dateTo' => $request->date_to,
        ];
    }

    private function getDiseaseSurveillanceData(Request $request): array
    {
        $diseases = PatientDiagnosis::query()
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('diagnosis_date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) -> $q->whereDate('diagnosis_date', '<=', $request->date_to))
            ->selectRaw('diagnosis, COUNT(*) as count')
            ->groupBy('diagnosis')->orderByDesc('count')->get();

        return [
            'diseases' => $diseases,
            'dateFrom' => $request->date_from,
            'dateTo' => $request->date_to,
        ];
    }

    private function getMaternalHealthData(Request $request): array
    {
        $query = BirthReport::query();
        if ($request->filled('date_from')) {
            $query->whereDate('birth_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('birth_date', '<=', $request->date_to);
        }

        return [
            'totalBirths' => (clone $query)->count(),
            'byDeliveryType' => (clone $query)->selectRaw('delivery_type, COUNT(*) as count')
                ->groupBy('delivery_type')->get(),
            'withComplications' => (clone $query)->whereNotNull('complications')->count(),
            'dateFrom' => $request->date_from,
            'dateTo' => $request->date_to,
        ];
    }

    private function getPharmacyConsumptionData(Request $request): array
    {
        $query = Prescription::with('items.medicine');
        if ($request->filled('date_from')) {
            $query->whereDate('prescription_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('prescription_date', '<=', $request->date_to);
        }

        return [
            'totalPrescriptions' => (clone $query)->count(),
            'totalMedicinesDispensed' => PrescriptionItem::whereHas('prescription', function ($q) use ($request) {
                $q->when($request->filled('date_from'), fn($q) => $q->whereDate('prescription_date', '>=', $request->date_from));
                $q->when($request->filled('date_to'), fn($q) => $q->whereDate('prescription_date', '<=', $request->date_to));
            })->sum('quantity'),
            'topMedicines' => PrescriptionItem::with('medicine')
                ->whereHas('prescription', function ($q) use ($request) {
                    $q->when($request->filled('date_from'), fn($q) => $q->whereDate('prescription_date', '>=', $request->date_from));
                    $q->when($request->filled('date_to'), fn($q) => $q->whereDate('prescription_date', '<=', $request->date_to));
                })
                ->selectRaw('medicine_id, SUM(quantity) as total_qty')
                ->groupBy('medicine_id')->orderByDesc('total_qty')->limit(10)->get(),
            'dateFrom' => $request->date_from,
            'dateTo' => $request->date_to,
        ];
    }

    private function getRevenueCollectionData(Request $request): array
    {
        $query = Payment::query();
        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        return [
            'totalCollected' => (clone $query)->sum('amount'),
            'byMethod' => (clone $query)->selectRaw('payment_method, SUM(amount) as total')
                ->groupBy('payment_method')->get(),
            'outstandingBalance' => Invoice::query()
                ->when($request->filled('date_from'), fn($q) => $q->whereDate('invoice_date', '>=', $request->date_from))
                ->when($request->filled('date_to'), fn($q) => $q->whereDate('invoice_date', '<=', $request->date_to))
                ->sum('balance_amount'),
            'dateFrom' => $request->date_from,
            'dateTo' => $request->date_to,
        ];
    }
}
