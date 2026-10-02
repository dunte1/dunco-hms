@extends('components.app-layout')

@section('title', 'Patient 360 — ' . $patient->full_name)

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Patient 360</h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ $patient->patient_no }} · {{ $patient->full_name }}
                @if($patient->gender) · {{ ucfirst($patient->gender) }} @endif
                @if($patient->dob) · DOB {{ \Carbon\Carbon::parse($patient->dob)->format('d M Y') }} @endif
            </p>
        </div>
        <a href="{{ route('hms.patients.show', $patient) }}"
           class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg text-sm hover:bg-gray-300">
            Back to record
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Demographics --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 font-semibold">Demographics</div>
            <div class="p-4">
                @if($sections['demographics']['allowed'] && $sections['demographics']['data'])
                    <dl class="grid grid-cols-2 gap-3 text-sm">
                        @foreach($sections['demographics']['data'] as $k => $v)
                            <div>
                                <dt class="text-gray-500 capitalize">{{ str_replace('_', ' ', $k) }}</dt>
                                <dd class="font-medium">{{ $v ?: '—' }}</dd>
                            </div>
                        @endforeach
                    </dl>
                @else
                    <p class="text-sm text-gray-500">You are not authorized to view demographics.</p>
                @endif
            </div>
        </div>

        {{-- Triage --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 font-semibold">Recent Triage</div>
            <div class="p-4">
                @if($sections['triage']['allowed'])
                    @if($sections['triage']['data']->isEmpty())
                        <p class="text-sm text-gray-500">No triage records.</p>
                    @else
                        <ul class="space-y-2 text-sm">
                            @foreach($sections['triage']['data'] as $t)
                                <li class="border-b pb-2">
                                    {{ optional($t->created_at)->format('d M Y H:i') }}
                                    @if($t->triage_category_id ?? null) · Category {{ $t->triage_category_id }} @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @else
                    <p class="text-sm text-gray-500">Not authorized for triage data.</p>
                @endif
            </div>
        </div>

        {{-- Prescriptions --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 font-semibold">Prescriptions</div>
            <div class="p-4">
                @if($sections['prescriptions']['allowed'])
                    @if($sections['prescriptions']['data']->isEmpty())
                        <p class="text-sm text-gray-500">No prescriptions.</p>
                    @else
                        <ul class="space-y-2 text-sm">
                            @foreach($sections['prescriptions']['data'] as $rx)
                                <li class="border-b pb-2">
                                    {{ $rx->prescription_date?->format('d M Y') ?? optional($rx->created_at)->format('d M Y') }}
                                    · {{ $rx->status }}
                                    · {{ $rx->items->count() }} item(s)
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @else
                    <p class="text-sm text-gray-500">Not authorized for prescriptions.</p>
                @endif
            </div>
        </div>

        {{-- Laboratory --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 font-semibold">Laboratory</div>
            <div class="p-4">
                @if($sections['laboratory']['allowed'])
                    @if($sections['laboratory']['data']->isEmpty())
                        <p class="text-sm text-gray-500">No lab requests.</p>
                    @else
                        <ul class="space-y-2 text-sm">
                            @foreach($sections['laboratory']['data'] as $lab)
                                <li class="border-b pb-2">
                                    {{ $lab->request_number ?? $lab->id }} · {{ $lab->status }} · {{ optional($lab->created_at)->format('d M Y') }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @else
                    <p class="text-sm text-gray-500">Not authorized for lab results.</p>
                @endif
            </div>
        </div>

        {{-- Radiology --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 font-semibold">Radiology</div>
            <div class="p-4">
                @if($sections['radiology']['allowed'])
                    @if($sections['radiology']['data']->isEmpty())
                        <p class="text-sm text-gray-500">No radiology requests.</p>
                    @else
                        <ul class="space-y-2 text-sm">
                            @foreach($sections['radiology']['data'] as $rad)
                                <li class="border-b pb-2">
                                    {{ $rad->request_number ?? $rad->id }} · {{ $rad->status ?? '—' }} · {{ optional($rad->created_at)->format('d M Y') }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @else
                    <p class="text-sm text-gray-500">Not authorized for radiology.</p>
                @endif
            </div>
        </div>

        {{-- Billing --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 font-semibold">Billing</div>
            <div class="p-4">
                @if($sections['billing']['allowed'])
                    @if(($sections['billing']['data'] ?? collect())->isEmpty())
                        <p class="text-sm text-gray-500">No invoices.</p>
                    @else
                        <ul class="space-y-2 text-sm">
                            @foreach($sections['billing']['data'] as $inv)
                                <li class="border-b pb-2">
                                    {{ $inv->invoice_number ?? $inv->id }} · {{ $inv->status }} · {{ number_format((float) ($inv->total_amount ?? $inv->total ?? $inv->amount ?? 0), 2) }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @else
                    <p class="text-sm text-gray-500">Not authorized for billing.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
