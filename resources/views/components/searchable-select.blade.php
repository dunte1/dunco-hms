@props([
    'name' => 'patient_id',
    'label' => 'Patient',
    'options' => [],
    'endpoint' => null,
    'selected' => null,
    'placeholder' => 'Type to search...',
    'required' => false,
    'value' => null,
])

@php
    $endpointUrl = $endpoint;
    if (! $endpointUrl) {
        $endpointUrl = Route::has('api.patients.search')
            ? route('api.patients.search')
            : url('/hms/api/patients/search');
    }
    $selectedLabel = null;
    $selectedValue = $value ?? old($name, $selected);

    if ($selectedValue && $options) {
        foreach ($options as $key => $option) {
            $optionLabel = is_array($option) ? ($option['label'] ?? $option['name'] ?? (string) $key) : (is_object($option) ? ($option->full_name ?? $option->name ?? (string) $option->id) : (string) $option);
            if ((string) $key === (string) $selectedValue) {
                $selectedLabel = $optionLabel;
                break;
            }
        }
    }
@endphp

<div
    class="w-full"
    x-data="searchableSelect({
        endpoint: @js($endpointUrl),
        selected: @js($selectedValue),
        selectedLabel: @js($selectedLabel),
        query: @js($selectedLabel),
        options: [],
        loading: false,
        open: false
    })"
    x-init="init()"
>
    @if($label)
        <label for="{{ $name }}_input" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}@if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif

    <div class="relative">
        <input
            type="text"
            id="{{ $name }}_input"
            x-model="query"
            x-ref="input"
            @focus="open = true; search()"
            @click.outside="open = false"
            @input.debounce.300ms="search()"
            placeholder="{{ $placeholder }}"
            autocomplete="off"
            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white bg-white px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 min-h-[42px]"
        >
        <input type="hidden" name="{{ $name }}" :value="selected" @if($required) required @endif>

        <div x-show="open && (options.length || loading)" x-transition.opacity
             class="absolute z-30 mt-1 w-full max-h-60 overflow-y-auto rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-lg">
            <div x-show="loading" class="px-3 py-2 text-sm text-gray-500">Searching...</div>
            <template x-for="option in options" :key="option.id">
                <button type="button"
                        @click="select(option)"
                        class="w-full text-left px-3 py-2 text-sm hover:bg-indigo-50 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-100"
                        x-text="option.label"></button>
            </template>
            <div x-show="!loading && !options.length" class="px-3 py-2 text-sm text-gray-500">No matches</div>
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script>
            function searchableSelect(config) {
                return {
                    endpoint: config.endpoint,
                    selected: config.selected || '',
                    selectedLabel: config.selectedLabel || '',
                    query: config.query || '',
                    options: config.options || [],
                    loading: false,
                    open: false,
                    init() {
                        if (this.selected && this.selectedLabel) {
                            this.query = this.selectedLabel;
                        }
                    },
                    async search() {
                        if (!this.endpoint) return;
                        this.loading = true;
                        try {
                            const url = this.endpoint + (this.endpoint.includes('?') ? '&' : '?') + 'q=' + encodeURIComponent(this.query || '');
                            const response = await fetch(url, { headers: { 'Accept': 'application/json' } });
                            const data = await response.json();
                            this.options = data.data || [];
                        } catch (e) {
                            this.options = [];
                        } finally {
                            this.loading = false;
                        }
                    },
                    select(option) {
                        this.selected = option.id;
                        this.selectedLabel = option.label;
                        this.query = option.label;
                        this.open = false;
                        this.options = [];
                        this.$dispatch('option-selected', { value: option.id, label: option.label });
                    },
                };
            }
        </script>
    @endpush
@endonce
