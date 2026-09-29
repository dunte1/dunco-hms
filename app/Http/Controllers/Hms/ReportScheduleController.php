<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ReportSchedule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReportScheduleController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'saved_report_id' => 'required|exists:saved_reports,id',
            'frequency' => 'required|string|in:daily,weekly,monthly',
            'day_of_week' => 'nullable|string',
            'day_of_month' => 'nullable|integer|min:1|max:31',
            'time_of_day' => 'required|date_format:H:i',
            'recipients' => 'required|array',
            'recipients.*' => 'email',
        ]);

        $data['next_run_at'] = $this->calculateNextRun($data);

        $schedule = ReportSchedule::create($data);

        return response()->json([
            'success' => true,
            'data' => $schedule,
            'message' => 'Report schedule created successfully',
        ], 201);
    }

    public function pause(ReportSchedule $schedule): JsonResponse
    {
        $schedule->update(['status' => 'paused']);

        return response()->json([
            'success' => true,
            'data' => $schedule,
            'message' => 'Report schedule paused',
        ]);
    }

    public function resume(ReportSchedule $schedule): JsonResponse
    {
        $schedule->update([
            'status' => 'active',
            'next_run_at' => $this->calculateNextRun($schedule->toArray()),
        ]);

        return response()->json([
            'success' => true,
            'data' => $schedule,
            'message' => 'Report schedule resumed',
        ]);
    }

    private function calculateNextRun(array $data): \Carbon\Carbon
    {
        $now = now();
        $time = $data['time_of_day'];

        $next = $now->copy()->setTimeFromTimeString($time);

        if ($data['frequency'] === 'daily') {
            if ($next->lte($now)) {
                $next->addDay();
            }
        } elseif ($data['frequency'] === 'weekly' && isset($data['day_of_week'])) {
            $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
            $targetDay = array_search(strtolower($data['day_of_week']), $days);
            $next->next($targetDay);
        } elseif ($data['frequency'] === 'monthly' && isset($data['day_of_month'])) {
            $next->day($data['day_of_month']);
            if ($next->lte($now)) {
                $next->addMonthNoOverflow();
            }
        }

        return $next;
    }
}
