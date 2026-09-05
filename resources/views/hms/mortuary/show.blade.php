<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2"><a href="{{ route('hms.mortuary.index') }}" class="hover:text-blue-600">Mortuary</a><i class="fa fa-chevron-right text-xs"></i><span>{{ $record->body_id }}</span></div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-skull-crossbones text-gray-600 mr-3"></i>{{ $record->body_id }}</h1>
                </div>
                <a href="{{ route('hms.mortuary.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
            </div>
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Details</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div><span class="text-gray-500">Body ID:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $record->body_id }}</span></div>
                        <div><span class="text-gray-500">Status:</span> <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $record->status === 'stored' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">{{ ucfirst($record->status) }}</span></div>
                        <div><span class="text-gray-500">Received:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $record->received_at?->format('M d, Y H:i') }}</span></div>
                        <div><span class="text-gray-500">Location:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $record->storage_location ?? 'N/A' }}</span></div>
                    </div>
                    @if($record->cause_of_death)<div><span class="text-xs text-gray-500 uppercase">Cause of Death</span><p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $record->cause_of_death }}</p></div>@endif
                    @if($record->family_contact_name)<div><span class="text-xs text-gray-500 uppercase">Family Contact</span><p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $record->family_contact_name }} {{ $record->family_contact_phone ? '(' . $record->family_contact_phone . ')' : '' }}</p></div>@endif
                </div>
                @if($record->status === 'stored' && !$record->release)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Release Body</h3>
                        <form action="{{ route('hms.mortuary.release', $record) }}" method="POST" class="space-y-4">
                            @csrf
                            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Released To *</label><input type="text" name="released_to_name" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Relation</label><input type="text" name="released_to_relation" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ID Number</label><input type="text" name="released_to_id_number" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Transport Method</label><input type="text" name="transport_method" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold">Release Body</button>
                        </form>
                    </div>
                @endif
                @if($record->release)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Release Info</h3>
                        <div class="text-sm space-y-2">
                            <p><span class="text-gray-500">Released To:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $record->release->released_to_name }}</span></p>
                            <p><span class="text-gray-500">Relation:</span> <span class="text-gray-700">{{ $record->release->released_to_relation ?? '-' }}</span></p>
                            <p><span class="text-gray-500">Released At:</span> <span class="text-gray-700">{{ $record->release->released_at?->format('M d, Y H:i') }}</span></p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
