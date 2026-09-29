<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\IpdAdmission;
use App\Models\DietOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DietOrderController extends Controller
{
    public function index(Request $request, IpdAdmission $ipd): View
    {
        $dietOrders = DietOrder::where('ipd_admission_id', $ipd->id)
            ->with(['doctor'])
            ->latest()
            ->paginate(15);

        return view('hms.ipd.diet-orders', compact('ipd', 'dietOrders'));
    }

    public function store(Request $request, IpdAdmission $ipd): RedirectResponse
    {
        $data = $request->validate([
            'diet_type' => 'required|in:normal,soft,liquid,NPO,diabetic,cardiac,renal',
            'instructions' => 'nullable|string',
            'ordered_by' => 'nullable|exists:doctors,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,discontinued',
        ]);

        $data['ipd_admission_id'] = $ipd->id;
        $data['patient_id'] = $ipd->patient_id;

        DietOrder::create($data);

        return back()->with('success', 'Diet order placed successfully!');
    }
}
