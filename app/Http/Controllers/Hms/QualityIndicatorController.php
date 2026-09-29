<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\QualityIndicator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QualityIndicatorController extends Controller
{
    public function index(): View
    {
        $indicators = QualityIndicator::with('values')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('hms.quality.indicators', compact('indicators'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:quality_indicators,code',
            'description' => 'required|string',
            'formula' => 'nullable|string',
            'target_value' => 'nullable|numeric',
            'unit' => 'nullable|string|max:50',
            'category' => 'required|in:clinical,operational,financial,patient_experience',
        ]);

        QualityIndicator::create($data);

        return back()->with('status', 'Quality indicator created');
    }
}
