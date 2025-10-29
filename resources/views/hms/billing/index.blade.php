@extends('admin.layouts.app')

@section('content')
    <div class="mb-4">
        @include('admin.partials.stats', ['stats' => [
            ['label' => 'Invoices', 'value' => 0],
            ['label' => 'Bills', 'value' => 0],
            ['label' => 'Payments', 'value' => 0],
            ['label' => 'Outstanding', 'value' => 0],
        ]])
    </div>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            {{ __('Billing module placeholder') }}
        </div>
    </div>
@endsection


