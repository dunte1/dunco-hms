<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Nurse;
use App\Models\OtRoom;
use App\Models\OtSchedule;
use App\Models\OtInstrumentTray;
use App\Models\OtTimeLog;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OtSchedulingController extends Controller
{
    public function index(Request $request): View
    {
        $query = OtSchedule::with(['patient', 'otRoom', 'surgeon', 'anesthetist']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->where('scheduled_date', $request->date);
        } else {
            $query->where('scheduled_date', '>=', now()->startOfWeek());
            $query->where('scheduled_date', '<=', now()->endOfWeek());
        }
        if ($request->filled('surgeon_id')) {
            $query->where('surgeon_id', $request->surgeon_id);
        }
        if ($request->filled('room_id')) {
            $query->where('ot_room_id', $request->room_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('schedule_number', 'like', "%{$search}%")
                  ->orWhere('procedure_name', 'like', "%{$search}%")
                  ->orWhereHas('patient', fn($pq) => $pq->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%"));
            });
        }

        $schedules = $query->orderBy('scheduled_date', 'desc')->orderBy('scheduled_start', 'desc')->paginate(15);
        $surgeons = Doctor::orderBy('first_name')->pluck('first_name', 'id');
        $rooms = OtRoom::orderBy('name')->pluck('name', 'id');
        $stats = $this->getStats();

        return view('hms.ot.index', compact('schedules', 'surgeons', 'rooms', 'stats'));
    }

    public function create(): View
    {
        $patients = Patient::orderBy('first_name')->pluck(function ($p) {
            return $p->first_name . ' ' . $p->last_name;
        }, 'id');
        $surgeons = Doctor::orderBy('first_name')->pluck(function ($d) {
            return $d->first_name . ' ' . $d->last_name;
        }, 'id');
        $anesthetists = Doctor::orderBy('first_name')->pluck(function ($d) {
            return $d->first_name . ' ' . $d->last_name;
        }, 'id');
        $nurses = Nurse::orderBy('first_name')->pluck(function ($n) {
            return $n->first_name . ' ' . $n->last_name;
        }, 'id');
        $rooms = OtRoom::available()->orderBy('name')->pluck('name', 'id');

        return view('hms.ot.create', compact('patients', 'surgeons', 'anesthetists', 'nurses', 'rooms'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'ot_room_id' => 'required|exists:ot_rooms,id',
            'surgeon_id' => 'required|exists:doctors,id',
            'anesthetist_id' => 'nullable|exists:doctors,id',
            'assistant_doctor_id' => 'nullable|exists:doctors,id',
            'nurse_id' => 'nullable|exists:nurses,id',
            'procedure_name' => 'required|string|max:255',
            'procedure_description' => 'nullable|string',
            'procedure_type' => 'required|in:elective,emergency,urgent',
            'anesthesia_type' => 'required|in:general,regional,local,spinal,epidural,none',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'scheduled_start' => 'required|date_format:H:i',
            'scheduled_end' => 'nullable|date_format:H:i|after:scheduled_start',
            'risk_level' => 'required|in:low,medium,high,critical',
            'pre_op_notes' => 'nullable|string',
            'estimated_cost' => 'nullable|numeric|min:0',
            'consent_signed' => 'nullable|boolean',
        ]);

        $data['schedule_number'] = OtSchedule::generateScheduleNumber();
        $data['status'] = 'scheduled';
        $data['created_by'] = auth()->id();

        $schedule = OtSchedule::create($data);

        return redirect()->route('hms.ot.show', $schedule)->with('status', 'OT schedule created successfully');
    }

    public function show(OtSchedule $schedule): View
    {
        $schedule->load(['patient', 'otRoom', 'surgeon', 'anesthetist', 'assistantDoctor', 'nurse', 'createdBy', 'timeLogs.recordedByUser']);
        $timeline = $this->buildTimeline($schedule);

        return view('hms.ot.show', compact('schedule', 'timeline'));
    }

    public function edit(OtSchedule $schedule): View
    {
        $patients = Patient::orderBy('first_name')->pluck(function ($p) {
            return $p->first_name . ' ' . $p->last_name;
        }, 'id');
        $surgeons = Doctor::orderBy('first_name')->pluck(function ($d) {
            return $d->first_name . ' ' . $d->last_name;
        }, 'id');
        $anesthetists = Doctor::orderBy('first_name')->pluck(function ($d) {
            return $d->first_name . ' ' . $d->last_name;
        }, 'id');
        $nurses = Nurse::orderBy('first_name')->pluck(function ($n) {
            return $n->first_name . ' ' . $n->last_name;
        }, 'id');
        $rooms = OtRoom::orderBy('name')->pluck('name', 'id');

        return view('hms.ot.edit', compact('schedule', 'patients', 'surgeons', 'anesthetists', 'nurses', 'rooms'));
    }

    public function update(Request $request, OtSchedule $schedule): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'ot_room_id' => 'required|exists:ot_rooms,id',
            'surgeon_id' => 'required|exists:doctors,id',
            'anesthetist_id' => 'nullable|exists:doctors,id',
            'assistant_doctor_id' => 'nullable|exists:doctors,id',
            'nurse_id' => 'nullable|exists:nurses,id',
            'procedure_name' => 'required|string|max:255',
            'procedure_description' => 'nullable|string',
            'procedure_type' => 'required|in:elective,emergency,urgent',
            'anesthesia_type' => 'required|in:general,regional,local,spinal,epidural,none',
            'scheduled_date' => 'required|date',
            'scheduled_start' => 'required|date_format:H:i',
            'scheduled_end' => 'nullable|date_format:H:i',
            'risk_level' => 'required|in:low,medium,high,critical',
            'pre_op_notes' => 'nullable|string',
            'post_op_notes' => 'nullable|string',
            'complications' => 'nullable|string',
            'estimated_cost' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'consent_signed' => 'nullable|boolean',
        ]);

        $schedule->update($data);

        return redirect()->route('hms.ot.show', $schedule)->with('status', 'OT schedule updated successfully');
    }

    public function destroy(OtSchedule $schedule): RedirectResponse
    {
        if (in_array($schedule->status, ['in_progress', 'completed'])) {
            return back()->with('error', 'Cannot cancel a schedule that is in progress or completed');
        }

        $schedule->update(['status' => 'cancelled']);

        return redirect()->route('hms.ot.index')->with('status', 'OT schedule cancelled');
    }

    public function schedule(): View
    {
        $date = request('date', now()->format('Y-m-d'));
        $rooms = OtRoom::orderBy('name')->get();
        $schedules = OtSchedule::with(['patient', 'surgeon', 'otRoom'])
            ->where('scheduled_date', $date)
            ->whereNotIn('status', ['cancelled'])
            ->orderBy('scheduled_start')
            ->get();

        return view('hms.ot.schedule', compact('schedules', 'rooms', 'date'));
    }

    public function rooms(): View
    {
        $rooms = OtRoom::withCount('schedules')->orderBy('name')->paginate(20);
        return view('hms.ot.rooms', compact('rooms'));
    }

    public function storeRoom(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|unique:ot_rooms,name',
            'floor' => 'nullable|string',
            'type' => 'required|in:general,cardiac,neuro,orthopedic,emergency,pediatric,ophthalmic',
            'equipment_notes' => 'nullable|string',
            'capacity' => 'nullable|integer|min:1',
        ]);

        OtRoom::create($data);

        return redirect()->route('hms.ot.rooms')->with('status', 'OT room created successfully');
    }

    public function updateRoom(Request $request, OtRoom $room): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|unique:ot_rooms,name,' . $room->id,
            'floor' => 'nullable|string',
            'type' => 'required|in:general,cardiac,neuro,orthopedic,emergency,pediatric,ophthalmic',
            'equipment_notes' => 'nullable|string',
            'status' => 'required|in:available,occupied,maintenance,cleaning',
            'capacity' => 'nullable|integer|min:1',
        ]);

        $room->update($data);

        return redirect()->route('hms.ot.rooms')->with('status', 'OT room updated successfully');
    }

    public function timeIn(OtSchedule $schedule): RedirectResponse
    {
        $schedule->update(['status' => 'in_progress', 'actual_start' => now()]);

        OtTimeLog::create([
            'ot_schedule_id' => $schedule->id,
            'event_type' => 'patient_arrival',
            'event_time' => now(),
            'recorded_by' => auth()->id(),
        ]);

        return back()->with('status', 'Patient marked as arrived in OT');
    }

    public function timeOut(OtSchedule $schedule): RedirectResponse
    {
        $schedule->update(['status' => 'completed', 'actual_end' => now()]);

        OtTimeLog::create([
            'ot_schedule_id' => $schedule->id,
            'event_type' => 'patient_transfer',
            'event_time' => now(),
            'recorded_by' => auth()->id(),
        ]);

        return back()->with('status', 'OT procedure completed');
    }

    public function instruments(): View
    {
        $instruments = OtInstrumentTray::with('lastUsedSchedule')->orderBy('name')->paginate(20);
        return view('hms.ot.instruments', compact('instruments'));
    }

    public function storeInstrument(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'category' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $data['status'] = 'sterile';
        $data['sterilized_at'] = now();
        $data['sterilization_expiry'] = now()->addHours(24);

        OtInstrumentTray::create($data);

        return redirect()->route('hms.ot.instruments')->with('status', 'Instrument tray added successfully');
    }

    public function sterilize(OtInstrumentTray $tray): RedirectResponse
    {
        $tray->update([
            'status' => 'sterile',
            'sterilized_at' => now(),
            'sterilization_expiry' => now()->addHours(24),
        ]);

        return back()->with('status', 'Instrument tray marked as sterilized');
    }

    private function getStats(): array
    {
        return [
            'today_total' => OtSchedule::where('scheduled_date', today())->count(),
            'today_completed' => OtSchedule::where('scheduled_date', today())->where('status', 'completed')->count(),
            'today_in_progress' => OtSchedule::where('scheduled_date', today())->where('status', 'in_progress')->count(),
            'today_scheduled' => OtSchedule::where('scheduled_date', today())->where('status', 'scheduled')->count(),
            'rooms_available' => OtRoom::where('status', 'available')->count(),
            'rooms_total' => OtRoom::count(),
            'week_total' => OtSchedule::whereBetween('scheduled_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];
    }

    private function buildTimeline(OtSchedule $schedule): array
    {
        $events = $schedule->timeLogs()->with('recordedByUser')->orderBy('event_time')->get();

        $labels = [
            'patient_arrival' => 'Patient Arrival',
            'prep_start' => 'Preparation Started',
            'anesthesia_start' => 'Anesthesia Started',
            'incision' => 'Incision',
            'procedure_start' => 'Procedure Started',
            'procedure_end' => 'Procedure Ended',
            'closure' => 'Closure',
            'patient_extubation' => 'Patient Extubation',
            'patient_transfer' => 'Patient Transfer',
        ];

        return $events->map(fn($log) => [
            'type' => $log->event_type,
            'label' => $labels[$log->event_type] ?? $log->event_type,
            'time' => $log->event_time->format('H:i'),
            'notes' => $log->notes,
            'recorded_by' => $log->recordedByUser?->name ?? 'System',
        ])->toArray();
    }
}
