<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\IpdAdmission;
use App\Models\FluidBalanceEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FluidBalanceController extends Controller
{
    public function index(Request $request, IpdAdmission $ipd): View
    {
        $entries = FluidBalanceEntry::where('ipd_admission_id', $ipd->id)
            ->with(['recorder'])
            ->latest('recorded_at')
            ->paginate(15);

        return view('hms.ipd.fluid-balance', compact('ipd', 'entries'));
    }

    public function store(Request $request, IpdAdmission $ipd): RedirectResponse
    {
        $data = $request->validate([
            'entry_type' => 'required|in:intake,output',
            'fluid_type' => 'required|in:oral,iv,blood,urine,emesis,drain',
            'amount_ml' => 'required|integer|min:1',
            'recorded_at' => 'required|date',
        ]);

        $data['ipd_admission_id'] = $ipd->id;
        $data['patient_id'] = $ipd->patient_id;
        $data['recorded_by'] = auth()->id();

        FluidBalanceEntry::create($data);

        return back()->with('success', 'Fluid balance entry recorded successfully!');
    }

    public function summary(IpdAdmission $ipd): View
    {
        $entries = FluidBalanceEntry::where('ipd_admission_id', $ipd->id)->get();

        $totalIntake = $entries->where('entry_type', 'intake')->sum('amount_ml');
        $totalOutput = $entries->where('entry_type', 'output')->sum('amount_ml');
        $balance = $totalIntake - $totalOutput;

        $intakeByType = $entries->where('entry_type', 'intake')->groupBy('fluid_type')
            ->map(fn($items) => $items->sum('amount_ml'));
        $outputByType = $entries->where('entry_type', 'output')->groupBy('fluid_type')
            ->map(fn($items) => $items->sum('amount_ml'));

        return view('hms.ipd.fluid-balance-summary', compact(
            'ipd', 'totalIntake', 'totalOutput', 'balance', 'intakeByType', 'outputByType'
        ));
    }
}
