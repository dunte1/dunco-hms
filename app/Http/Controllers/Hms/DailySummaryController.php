<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\OpdVisit;
use App\Models\IpdAdmission;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class DailySummaryController extends Controller
{
    /**
     * Display daily summary dashboard
     */
    public function index(): View
    {
        $today = now();
        $summary = $this->generateSummary($today);
        
        return view('hms.ai.daily-summary', compact('summary'));
    }

    /**
     * Generate daily summary for a specific date
     */
    public function generate(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $summary = $this->generateSummary(Carbon::parse($date));
        
        return response()->json($summary);
    }

    /**
     * Auto-generate and email daily summary
     */
    public function autoGenerate()
    {
        $summary = $this->generateSummary(now());
        
        // Email to administrators
        $recipients = config('hms.daily_summary_recipients', []);
        
        if (!empty($recipients)) {
            foreach ($recipients as $email) {
                Mail::send('emails.daily-summary', ['summary' => $summary], function ($message) use ($email, $summary) {
                    $message->to($email)
                            ->subject('Daily Hospital Summary - ' . now()->format('F d, Y'));
                });
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Daily summary generated and sent',
            'summary' => $summary,
        ]);
    }

    /**
     * Generate summary data
     */
    private function generateSummary($date): array
    {
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();
        
        return [
            'date' => $date->format('Y-m-d'),
            'patients' => [
                'new' => Patient::whereDate('created_at', $date)->count(),
                'total' => Patient::count(),
                'active' => Patient::whereHas('appointments', function($q) use ($startOfDay, $endOfDay) {
                    $q->whereBetween('appointment_date', [$startOfDay, $endOfDay]);
                })->count(),
            ],
            'appointments' => [
                'scheduled' => Appointment::whereDate('appointment_date', $date)->count(),
                'completed' => Appointment::whereDate('appointment_date', $date)
                    ->where('status', 'completed')->count(),
                'cancelled' => Appointment::whereDate('appointment_date', $date)
                    ->where('status', 'cancelled')->count(),
            ],
            'opd' => [
                'visits' => OpdVisit::whereDate('visit_date', $date)->count(),
                'revenue' => OpdVisit::whereDate('visit_date', $date)->sum('total_amount'),
            ],
            'ipd' => [
                'admissions' => IpdAdmission::whereDate('admission_date', $date)->count(),
                'discharges' => IpdAdmission::whereDate('discharge_date', $date)->count(),
                'current' => IpdAdmission::whereNull('discharge_date')->count(),
            ],
            'billing' => [
                'invoices' => Invoice::whereDate('invoice_date', $date)->count(),
                'revenue' => Invoice::whereDate('invoice_date', $date)->sum('total_amount'),
                'collections' => Invoice::whereDate('invoice_date', $date)
                    ->where('status', 'paid')->sum('total_amount'),
            ],
            'top_doctors' => $this->getTopDoctors($date),
            'alerts' => $this->getAlerts($date),
        ];
    }

    /**
     * Get top performing doctors
     */
    private function getTopDoctors($date): array
    {
        return \App\Models\Doctor::withCount(['appointments' => function($q) use ($date) {
            $q->whereDate('appointment_date', $date);
        }])
        ->orderBy('appointments_count', 'desc')
        ->limit(5)
        ->get(['id', 'first_name', 'last_name', 'appointments_count'])
        ->map(function($doctor) {
            return [
                'name' => $doctor->first_name . ' ' . $doctor->last_name,
                'appointments' => $doctor->appointments_count,
            ];
        })
        ->toArray();
    }

    /**
     * Get system alerts
     */
    private function getAlerts($date): array
    {
        $alerts = [];
        
        // Low stock medicines
        $lowStock = \App\Models\Medicine::where('stock_quantity', '<', 10)->count();
        if ($lowStock > 0) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "{$lowStock} medicines are running low on stock",
            ];
        }
        
        // Pending appointments
        $pendingAppointments = Appointment::whereDate('appointment_date', $date)
            ->where('status', 'pending')->count();
        if ($pendingAppointments > 0) {
            $alerts[] = [
                'type' => 'info',
                'message' => "{$pendingAppointments} appointments pending confirmation",
            ];
        }
        
        // Unpaid invoices
        $unpaidInvoices = Invoice::where('status', 'pending')
            ->where('total_amount', '>', 0)->count();
        if ($unpaidInvoices > 0) {
            $alerts[] = [
                'type' => 'danger',
                'message' => "{$unpaidInvoices} unpaid invoices require attention",
            ];
        }
        
        return $alerts;
    }

    /**
     * Schedule auto-generation (for cron)
     */
    public function schedule()
    {
        // This would be called by Laravel scheduler daily
        return $this->autoGenerate();
    }
}
