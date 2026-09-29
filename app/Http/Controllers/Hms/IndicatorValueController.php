<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\IndicatorValue;
use App\Models\QualityIndicator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class IndicatorValueController extends Controller
{
    public function store(Request $request, QualityIndicator $indicator): RedirectResponse
    {
        $data = $request->validate([
            'period_month' => 'required|integer|min:1|max:12',
            'period_year' => 'required|integer|min:2020|max:2100',
            'numerator' => 'nullable|numeric',
            'denominator' => 'nullable|numeric',
            'actual_value' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $data['indicator_id'] = $indicator->id;
        $data['recorded_by'] = auth()->id();
        $data['recorded_at'] = now();

        if (!empty($data['numerator']) && !empty($data['denominator']) && $data['denominator'] > 0) {
            $computed = round(($data['numerator'] / $data['denominator']) * 100, 4);
            $data['actual_value'] = $data['actual_value'] ?? $computed;
            if ($indicator->target_value !== null) {
                $data['status'] = $computed >= $indicator->target_value ? 'met' : 'not_met';
            } else {
                $data['status'] = 'insufficient_data';
            }
        } else {
            $data['status'] = $data['status'] ?? 'insufficient_data';
        }

        IndicatorValue::create($data);

        return back()->with('status', 'Indicator value recorded');
    }
}
