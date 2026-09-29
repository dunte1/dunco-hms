<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\PartnerNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PartnerNotificationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'partner_name' => 'nullable|string',
            'partner_phone' => 'nullable|string',
            'partner_traced' => 'boolean',
            'notification_method' => 'required|in:self,facility,unknown',
            'hts_encounter_id' => 'nullable|exists:hts_encounters,id',
        ]);

        $data['status'] = 'pending';

        PartnerNotification::create($data);

        return redirect()->back()->with('success', 'Partner notification recorded successfully!');
    }

    public function update(Request $request, PartnerNotification $notification): RedirectResponse
    {
        $data = $request->validate([
            'status' => 'required|in:notified,tested,linked',
            'notified_at' => 'nullable|date',
            'tested_at' => 'nullable|date',
        ]);

        $notification->update($data);

        return redirect()->back()->with('success', 'Partner notification status updated!');
    }
}
