<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\CorrectiveAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CorrectiveActionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'source_type' => 'required|in:complaint,incident,audit,indicator',
            'source_id' => 'required|integer',
            'action_description' => 'required|string',
            'responsible_person' => 'required|exists:users,id',
            'due_date' => 'required|date',
        ]);

        $data['status'] = 'pending';

        CorrectiveAction::create($data);

        return back()->with('status', 'Corrective action created');
    }

    public function complete(Request $request, CorrectiveAction $action): RedirectResponse
    {
        $data = $request->validate([
            'evidence_path' => 'nullable|string|max:255',
        ]);

        $action->complete($data['evidence_path'] ?? null);

        return back()->with('status', 'Corrective action completed');
    }
}
