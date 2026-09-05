<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.drug-interactions.index') }}" class="hover:text-blue-600">Drug Interactions</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Details</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-exclamation-triangle text-red-600 mr-3"></i>
                    Interaction Details
                </h1>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center gap-4 mb-6">
                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $drugInteraction->severity_badge }}">{{ ucfirst($drugInteraction->severity) }}</span>
                    <span class="text-sm text-gray-500">{{ $drugInteraction->source ?? 'No source specified' }}</span>
                </div>
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div><span class="text-xs text-gray-500 uppercase">Drug A</span><p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $drugInteraction->drugA->name }}</p></div>
                    <div><span class="text-xs text-gray-500 uppercase">Drug B</span><p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $drugInteraction->drugB->name }}</p></div>
                </div>
                <div class="space-y-4">
                    <div><span class="text-xs text-gray-500 uppercase">Description</span><p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $drugInteraction->description }}</p></div>
                    @if($drugInteraction->clinical_effect)
                        <div><span class="text-xs text-gray-500 uppercase">Clinical Effect</span><p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $drugInteraction->clinical_effect }}</p></div>
                    @endif
                    @if($drugInteraction->management_advice)
                        <div><span class="text-xs text-gray-500 uppercase">Management Advice</span><p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $drugInteraction->management_advice }}</p></div>
                    @endif
                </div>
                <div class="flex gap-4 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('hms.drug-interactions.edit', $drugInteraction) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm"><i class="fa fa-edit mr-1"></i> Edit</a>
                    <a href="{{ route('hms.drug-interactions.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
