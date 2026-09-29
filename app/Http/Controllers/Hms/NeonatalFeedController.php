<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Newborn;
use App\Models\NeonatalFeed;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NeonatalFeedController extends Controller
{
    public function store(Request $request, Newborn $newborn): RedirectResponse
    {
        $data = $request->validate([
            'feed_time' => 'required|date',
            'feed_type' => 'required|in:breast,formula,tpn,expressed',
            'volume_ml' => 'nullable|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:1',
            'method' => 'required|in:suckling,cup,spoon,tube',
            'notes' => 'nullable|string',
        ]);

        $data['newborn_id'] = $newborn->id;
        $data['recorded_by'] = auth()->id();

        NeonatalFeed::create($data);

        return redirect()->route('hms.neonatal.newborns.index')
            ->with('status', 'Feeding record saved.');
    }
}
