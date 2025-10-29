<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
    @foreach(($stats ?? [
        ['label' => 'Total Patients', 'value' => 0],
        ['label' => 'Total Doctors', 'value' => 0],
        ['label' => 'Available Beds', 'value' => 0],
        ['label' => 'Invoices (today)', 'value' => 0],
    ]) as $card)
        <div class="bg-white p-4 rounded shadow">
            <div class="text-xs text-gray-500">{{ $card['label'] }}</div>
            <div class="text-2xl font-semibold">{{ $card['value'] }}</div>
        </div>
    @endforeach
    @if(!empty($extra))
        @foreach($extra as $card)
            <div class="bg-white p-4 rounded shadow">
                <div class="text-xs text-gray-500">{{ $card['label'] }}</div>
                <div class="text-2xl font-semibold">{{ $card['value'] }}</div>
            </div>
        @endforeach
    @endif
</div>


