<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\SterilizerRun;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SterilizerRunController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'sterilizer_name' => 'required|string',
            'load_number' => 'required|integer|min:1',
            'temperature' => 'nullable|numeric',
            'pressure' => 'nullable|numeric',
            'exposure_time_minutes' => 'nullable|integer|min:1',
            'cycle_type' => 'required|in:gravity,pre_vac,post_vac',
        ]);
        $data['start_time'] = now();
        $data['status'] = 'completed';
        $data['operator_id'] = auth()->id();
        SterilizerRun::create($data);
        return back()->with('status', 'Sterilizer run recorded successfully');
    }
}
