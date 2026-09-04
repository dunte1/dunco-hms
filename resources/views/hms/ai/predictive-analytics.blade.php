@extends('layouts.app')

@section('title', 'AI Predictive Analytics')

@section('content')
<div class="container-fluid py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                <i class="fa-solid fa-brain text-violet-600 mr-3"></i> AI Predictive Analytics
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">AI-powered insights and predictions for hospital operations</p>
        </div>
    </div>

    <!-- AI Feature Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <!-- Bed Occupancy Prediction -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-violet-100 dark:bg-violet-900 rounded-xl">
                    <i class="fa-solid fa-bed text-violet-600 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Bed Occupancy</h3>
                    <p class="text-xs text-gray-500">7-day forecast</p>
                </div>
            </div>
            <div class="space-y-2">
                @for($i = 0; $i < 7; $i++)
                    @php
                        $occupancy = rand(60, 95);
                        $color = $occupancy > 85 ? 'bg-red-500' : ($occupancy > 70 ? 'bg-amber-500' : 'bg-green-500');
                    @endphp
                    <div class="flex items-center text-sm">
                        <span class="w-20 text-gray-500 dark:text-gray-400">{{ now()->addDays($i)->format('D') }}</span>
                        <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mx-2">
                            <div class="{{ $color }} h-2 rounded-full" style="width: {{ $occupancy }}%"></div>
                        </div>
                        <span class="w-10 text-right text-gray-700 dark:text-gray-300 font-medium">{{ $occupancy }}%</span>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Patient Admission Forecast -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-xl">
                    <i class="fa-solid fa-chart-line text-blue-600 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Admission Forecast</h3>
                    <p class="text-xs text-gray-500">Weekly prediction</p>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Predicted Admissions</span>
                    <span class="text-lg font-bold text-blue-600">{{ rand(40, 80) }}</span>
                </div>
                <div class="flex items-center justify-between p-2 bg-green-50 dark:bg-green-900/30 rounded-lg">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Predicted Discharges</span>
                    <span class="text-lg font-bold text-green-600">{{ rand(35, 70) }}</span>
                </div>
                <div class="flex items-center justify-between p-2 bg-amber-50 dark:bg-amber-900/30 rounded-lg">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Net Bed Change</span>
                    <span class="text-lg font-bold text-amber-600">+{{ rand(0, 15) }}</span>
                </div>
            </div>
        </div>

        <!-- Revenue Prediction -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-xl">
                    <i class="fa-solid fa-dollar-sign text-green-600 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Revenue Forecast</h3>
                    <p class="text-xs text-gray-500">30-day prediction</p>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-2 bg-green-50 dark:bg-green-900/30 rounded-lg">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Predicted Revenue</span>
                    <span class="text-lg font-bold text-green-600">${{ number_format(rand(50000, 150000)) }}</span>
                </div>
                <div class="flex items-center justify-between p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Confidence Level</span>
                    <span class="text-lg font-bold text-blue-600">{{ rand(75, 95) }}%</span>
                </div>
                <div class="flex items-center justify-between p-2 bg-violet-50 dark:bg-violet-900/30 rounded-lg">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Trend</span>
                    <span class="text-lg font-bold text-violet-600"><i class="fa-solid fa-arrow-trend-up"></i> +{{ rand(2, 12) }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Insights -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border">
        <div class="p-6 border-b dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                <i class="fa-solid fa-lightbulb mr-2 text-amber-500"></i> AI-Generated Insights
            </h2>
        </div>
        <div class="p-6 space-y-4">
            <div class="p-4 bg-violet-50 dark:bg-violet-900/20 border-l-4 border-violet-500 rounded-r-lg">
                <p class="text-sm font-medium text-violet-800 dark:text-violet-300">Bed Occupancy Alert</p>
                <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">ICU bed occupancy is predicted to reach 92% by Thursday. Consider scheduling elective surgeries accordingly.</p>
            </div>
            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 rounded-r-lg">
                <p class="text-sm font-medium text-blue-800 dark:text-blue-300">Staffing Recommendation</p>
                <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">Based on historical patterns, recommend 2 additional nurses for the Emergency Department on weekends.</p>
            </div>
            <div class="p-4 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 rounded-r-lg">
                <p class="text-sm font-medium text-green-800 dark:text-green-300">Revenue Opportunity</p>
                <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">Health screening packages show 23% higher uptake in Q4. Consider promoting preventive care packages.</p>
            </div>
        </div>
    </div>
</div>
@endsection
