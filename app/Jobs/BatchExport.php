<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\AuditLog;
use App\Notifications\ExportReadyNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GenericExport;

class BatchExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $module;
    public $userId;
    public $dateFrom;
    public $dateTo;
    public $format;

    /**
     * Create a new job instance.
     */
    public function __construct(string $module, int $userId, $dateFrom = null, $dateTo = null, string $format = 'csv')
    {
        $this->module = $module;
        $this->userId = $userId;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->format = $format;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::find($this->userId);
        if (!$user) {
            return;
        }

        try {
            $filename = $this->generateExport();
            $user->notify(new ExportReadyNotification($filename, $this->format));
        } catch (\Exception $e) {
            \Log::error('Batch export failed for module ' . $this->module . ': ' . $e->getMessage());
        }
    }

    /**
     * Generate export file based on module and format
     */
    private function generateExport(): string
    {
        $data = $this->getModuleData();
        $timestamp = date('Y-m-d_His');
        $baseName = $this->module . '_' . $timestamp;

        switch ($this->format) {
            case 'pdf':
                return $this->generatePdf($data, $baseName);
            case 'excel':
            case 'xlsx':
                return $this->generateExcel($data, $baseName);
            case 'csv':
            default:
                return $this->generateCsv($data, $baseName);
        }
    }

    /**
     * Get data for the specified module
     */
    private function getModuleData(): array
    {
        $query = match ($this->module) {
            'patients' => Patient::query(),
            'appointments' => Appointment::with(['patient', 'doctor']),
            'employees' => Employee::with('department'),
            'payments' => Payment::with('invoice.patient'),
            'invoices' => Invoice::with('patient'),
            default => null,
        };

        if (!$query) {
            return [];
        }

        if ($this->dateFrom) {
            $dateColumn = $this->getDateColumn();
            $query->whereDate($dateColumn, '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $dateColumn = $this->getDateColumn();
            $query->whereDate($dateColumn, '<=', $this->dateTo);
        }

        return $query->latest()->get()->toArray();
    }

    /**
     * Get the appropriate date column for the module
     */
    private function getDateColumn(): string
    {
        return match ($this->module) {
            'patients' => 'created_at',
            'appointments' => 'scheduled_at',
            'employees' => 'hire_date',
            'payments' => 'payment_date',
            'invoices' => 'invoice_date',
            default => 'created_at',
        };
    }

    /**
     * Generate PDF export
     */
    private function generatePdf(array $data, string $baseName): string
    {
        $filename = 'exports/' . $baseName . '.pdf';
        $viewMap = [
            'patients' => 'hms.reports.patients-pdf',
            'invoices' => 'hms.reports.billing-pdf',
            'payments' => 'hms.reports.revenue-pdf',
        ];

        $view = $viewMap[$this->module] ?? 'hms.reports.generated-report';
        $collection = collect($data);

        $pdf = Pdf::loadView($view, [
            'patients' => $this->module === 'patients' ? $collection : null,
            'invoices' => $this->module === 'invoices' ? $collection : null,
            'payments' => $this->module === 'payments' ? $collection : null,
            'data' => $data,
            'columns' => array_keys($data[0] ?? []),
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
        ]);

        Storage::disk('public')->put($filename, $pdf->output());
        return $filename;
    }

    /**
     * Generate Excel export
     */
    private function generateExcel(array $data, string $baseName): string
    {
        $filename = 'exports/' . $baseName . '.xlsx';

        if (empty($data)) {
            Storage::disk('public')->put($filename, '');
            return $filename;
        }

        $headers = array_keys($data[0]);
        $collection = collect($data);

        Excel::store(new GenericExport($collection, $headers), $filename, 'public');
        return $filename;
    }

    /**
     * Generate CSV export
     */
    private function generateCsv(array $data, string $baseName): string
    {
        $filename = 'exports/' . $baseName . '.csv';
        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            if (!empty($data)) {
                fputcsv($file, array_keys($data[0]));
                foreach ($data as $row) {
                    fputcsv($file, $row);
                }
            }
            fclose($file);
        };

        $csvContent = fopen('php://temp', 'r+');
        fputcsv($csvContent, !empty($data) ? array_keys($data[0]) : []);
        if (!empty($data)) {
            foreach ($data as $row) {
                fputcsv($csvContent, $row);
            }
        }
        rewind($csvContent);
        $csvString = stream_get_contents($csvContent);
        fclose($csvContent);

        Storage::disk('public')->put($filename, $csvString);
        return $filename;
    }
}
