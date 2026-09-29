<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ProcedureOrder;
use App\Models\OpdVisit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProcedureOrderController extends Controller
{
    public function store(Request $request, OpdVisit $opd): RedirectResponse
    {
        $data = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'procedure_name' => 'required|string',
            'description' => 'nullable|string',
            'body_site' => 'nullable|string',
        ]);

        ProcedureOrder::create([
            'patient_id' => $opd->patient_id,
            'opd_visit_id' => $opd->id,
            'doctor_id' => $data['doctor_id'],
            'procedure_name' => $data['procedure_name'],
            'description' => $data['description'] ?? null,
            'body_site' => $data['body_site'] ?? null,
            'status' => 'ordered',
        ]);

        return back()->with('success', 'Procedure ordered.');
    }

    public function complete(Request $request, ProcedureOrder $order): RedirectResponse
    {
        $data = $request->validate([
            'performed_by' => 'required|exists:users,id',
            'performed_at' => 'required|date',
            'outcome' => 'nullable|string',
            'complications' => 'nullable|string',
        ]);

        $order->update([
            'status' => 'completed',
            'performed_by' => $data['performed_by'],
            'performed_at' => $data['performed_at'],
            'outcome' => $data['outcome'] ?? null,
            'complications' => $data['complications'] ?? null,
        ]);

        return back()->with('success', 'Procedure marked as completed.');
    }
}
