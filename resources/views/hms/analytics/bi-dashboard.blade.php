<x-app-layout>
<div class="p-6 space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                <i class="fas fa-chart-line mr-3 text-blue-600"></i>
                Business Intelligence Dashboard
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Advanced Analytics & Predictive Insights</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="window.print()" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition flex items-center">
                <i class="fas fa-print mr-2"></i> Print
            </button>
            <button onclick="exportDashboard()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                <i class="fas fa-download mr-2"></i> Export
            </button>
        </div>
    </div>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Patients -->
        <div class="relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
            <div class="p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <span class="text-sm font-medium bg-white bg-opacity-20 px-3 py-1 rounded-full">+12.5%</span>
                </div>
                <h3 class="text-4xl font-bold mb-1">{{ number_format($metrics['total_patients']) }}</h3>
                <p class="text-blue-100 text-sm font-medium">Total Patients</p>
                <div class="mt-4 pt-4 border-t border-white border-opacity-20">
                    <a href="{{ route('hms.patients.index') }}" class="text-sm hover:text-blue-100 transition flex items-center">
                        View all patients <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
            </div>
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-16 -mt-16"></div>
        </div>

        <!-- Total Revenue -->
        <div class="relative overflow-hidden bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg">
            <div class="p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                        <i class="fas fa-dollar-sign text-2xl"></i>
                    </div>
                    <span class="text-sm font-medium bg-white bg-opacity-20 px-3 py-1 rounded-full">+8.3%</span>
                </div>
                <h3 class="text-4xl font-bold mb-1">${{ number_format($metrics['total_revenue'], 2) }}</h3>
                <p class="text-green-100 text-sm font-medium">Total Revenue</p>
                <div class="mt-4 pt-4 border-t border-white border-opacity-20">
                    <a href="{{ route('hms.reports.revenue') }}" class="text-sm hover:text-green-100 transition flex items-center">
                        View revenue report <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
            </div>
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-16 -mt-16"></div>
        </div>

        <!-- Bed Occupancy -->
        <div class="relative overflow-hidden bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg">
            <div class="p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                        <i class="fas fa-bed text-2xl"></i>
                    </div>
                    <span class="text-sm font-medium bg-white bg-opacity-20 px-3 py-1 rounded-full">{{ number_format($metrics['bed_occupancy'], 1) }}%</span>
                </div>
                <h3 class="text-4xl font-bold mb-1">{{ number_format($metrics['bed_occupancy'], 1) }}%</h3>
                <p class="text-purple-100 text-sm font-medium">Bed Occupancy Rate</p>
                <div class="mt-4 pt-4 border-t border-white border-opacity-20">
                    <div class="w-full bg-white bg-opacity-20 rounded-full h-2">
                        <div class="bg-white h-2 rounded-full" style="width: {{ $metrics['bed_occupancy'] }}%"></div>
                    </div>
                </div>
            </div>
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-16 -mt-16"></div>
        </div>

        <!-- Patient Satisfaction -->
        <div class="relative overflow-hidden bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg">
            <div class="p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                        <i class="fas fa-star text-2xl"></i>
                    </div>
                    <span class="text-sm font-medium bg-white bg-opacity-20 px-3 py-1 rounded-full">Excellent</span>
                </div>
                <h3 class="text-4xl font-bold mb-1">{{ number_format($metrics['patient_satisfaction'], 1) }}%</h3>
                <p class="text-orange-100 text-sm font-medium">Patient Satisfaction</p>
                <div class="mt-4 pt-4 border-t border-white border-opacity-20">
                    <div class="flex items-center">
                        <i class="fas fa-star text-sm mr-1"></i>
                        <i class="fas fa-star text-sm mr-1"></i>
                        <i class="fas fa-star text-sm mr-1"></i>
                        <i class="fas fa-star text-sm mr-1"></i>
                        <i class="fas fa-star-half-alt text-sm"></i>
                    </div>
                </div>
            </div>
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-16 -mt-16"></div>
        </div>
    </div>

    <!-- Analytics Generator -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                <i class="fas fa-chart-bar mr-2 text-blue-600"></i>
                Generate Custom Analytics
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Create detailed analytics reports based on your criteria</p>
        </div>
        <div class="p-6">
            <form id="analyticsForm" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Metric Type</label>
                        <select name="metric_type" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" required>
                            <option value="">Select Metric</option>
                            <option value="revenue">Revenue Analytics</option>
                            <option value="patient_count">Patient Count</option>
                            <option value="occupancy">Bed Occupancy</option>
                            <option value="satisfaction">Patient Satisfaction</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From Date</label>
                        <input type="date" name="date_from" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To Date</label>
                        <input type="date" name="date_to" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Granularity</label>
                        <select name="granularity" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" required>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                        <i class="fas fa-chart-line mr-2"></i> Generate Analytics
                    </button>
                    <button type="reset" class="px-6 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        <i class="fas fa-redo mr-2"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Analytics Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-chart-area mr-2 text-green-600"></i>
                        Revenue Analytics
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Monthly revenue trends</p>
                </div>
                <div class="flex space-x-2">
                    <button class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                        <i class="fas fa-expand text-gray-600 dark:text-gray-400"></i>
                    </button>
                    <button class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                        <i class="fas fa-download text-gray-600 dark:text-gray-400"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>

        <!-- Patient Analytics Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-users mr-2 text-blue-600"></i>
                        Patient Analytics
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Patient admission trends</p>
                </div>
                <div class="flex space-x-2">
                    <button class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                        <i class="fas fa-expand text-gray-600 dark:text-gray-400"></i>
                    </button>
                    <button class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                        <i class="fas fa-download text-gray-600 dark:text-gray-400"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <canvas id="patientChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Predictions Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Predictions -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-gray-800 dark:to-gray-800 rounded-xl shadow-sm border border-green-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-green-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-brain mr-2 text-green-600"></i>
                    AI Revenue Predictions
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Machine learning forecast</p>
            </div>
            <div class="p-6">
                <div id="revenuePredictions">
                    <div class="flex items-center justify-center py-8">
                        <div class="text-center">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div>
                            <p class="text-gray-600 dark:text-gray-400 mt-3">Loading predictions...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Patient Predictions -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-800 rounded-xl shadow-sm border border-blue-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-blue-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-brain mr-2 text-blue-600"></i>
                    AI Patient Predictions
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Predictive patient flow analysis</p>
            </div>
            <div class="p-6">
                <div id="patientPredictions">
                    <div class="flex items-center justify-center py-8">
                        <div class="text-center">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                            <p class="text-gray-600 dark:text-gray-400 mt-3">Loading predictions...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Analytics Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                <i class="fas fa-history mr-2 text-purple-600"></i>
                Recent Analytics History
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Previously generated analytics reports</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Metric Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date Range</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Granularity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($analytics as $analytic)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $analytic->metric_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $analytic->metric_type === 'revenue' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 
                                   ($analytic->metric_type === 'patient_count' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : 
                                   'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300') }}">
                                {{ ucfirst($analytic->metric_type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                            {{ $analytic->date_from }} to {{ $analytic->date_to }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ ucfirst($analytic->granularity) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                            {{ $analytic->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex space-x-2">
                                <button onclick="viewAnalytic({{ $analytic->id }})" class="px-3 py-1.5 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-800 transition flex items-center">
                                    <i class="fas fa-eye text-xs mr-1"></i> View
                                </button>
                                <button onclick="exportAnalytic({{ $analytic->id }})" class="px-3 py-1.5 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-lg hover:bg-green-200 dark:hover:bg-green-800 transition flex items-center">
                                    <i class="fas fa-download text-xs mr-1"></i> Export
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-chart-bar text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                                <p class="text-gray-500 dark:text-gray-400 font-medium">No analytics generated yet</p>
                                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Use the form above to generate your first analytics report</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart.js Configuration
Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.color = '#6B7280';

// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Revenue ($)',
            data: [12000, 19000, 15000, 25000, 22000, 30000, 28000, 32000, 35000, 38000, 42000, 45000],
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            tension: 0.4,
            fill: true,
            pointRadius: 4,
            pointHoverRadius: 6,
            pointBackgroundColor: 'rgb(34, 197, 94)',
            borderWidth: 3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    usePointStyle: true,
                    padding: 15
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: { size: 14 },
                bodyFont: { size: 13 },
                cornerRadius: 8
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                },
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Patient Chart
const patientCtx = document.getElementById('patientChart').getContext('2d');
const patientChart = new Chart(patientCtx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Patients',
            data: [65, 59, 80, 81, 56, 75, 88, 92, 85, 90, 95, 100],
            backgroundColor: 'rgba(59, 130, 246, 0.8)',
            borderColor: 'rgb(59, 130, 246)',
            borderWidth: 2,
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    usePointStyle: true,
                    padding: 15
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: { size: 14 },
                bodyFont: { size: 13 },
                cornerRadius: 8
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Load predictions
function loadRevenuePredictions() {
    fetch('/hms/analytics/revenue')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const predictions = data.data.predictions;
                const html = `
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="bg-white dark:bg-gray-700 rounded-lg p-4">
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Next Month</div>
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">$${predictions.next_month.toLocaleString()}</div>
                        </div>
                        <div class="bg-white dark:bg-gray-700 rounded-lg p-4">
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Next Quarter</div>
                            <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">$${predictions.next_quarter.toLocaleString()}</div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Confidence Level</span>
                            <span class="text-sm font-semibold text-green-600 dark:text-green-400">${predictions.confidence}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-3">
                            <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-3 rounded-full transition-all duration-500" style="width: ${predictions.confidence}%"></div>
                        </div>
                    </div>
                `;
                document.getElementById('revenuePredictions').innerHTML = html;
            }
        })
        .catch(error => {
            document.getElementById('revenuePredictions').innerHTML = `
                <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 mr-2"></i>
                        <span class="text-yellow-800 dark:text-yellow-200">Unable to load predictions</span>
                    </div>
                </div>
            `;
        });
}

function loadPatientPredictions() {
    fetch('/hms/analytics/patients')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const predictions = data.data.predictions;
                const html = `
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="bg-white dark:bg-gray-700 rounded-lg p-4">
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Next Month</div>
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">${predictions.next_month.toLocaleString()}</div>
                        </div>
                        <div class="bg-white dark:bg-gray-700 rounded-lg p-4">
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Next Quarter</div>
                            <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">${predictions.next_quarter.toLocaleString()}</div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Confidence Level</span>
                            <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">${predictions.confidence}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-3">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-500 h-3 rounded-full transition-all duration-500" style="width: ${predictions.confidence}%"></div>
                        </div>
                    </div>
                `;
                document.getElementById('patientPredictions').innerHTML = html;
            }
        })
        .catch(error => {
            document.getElementById('patientPredictions').innerHTML = `
                <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 mr-2"></i>
                        <span class="text-yellow-800 dark:text-yellow-200">Unable to load predictions</span>
                    </div>
                </div>
            `;
        });
}

// Form submission
document.getElementById('analyticsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Generating...';
    submitBtn.disabled = true;
    
    fetch('{{ route("analytics.generate") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success notification
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            notification.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Analytics generated successfully!';
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
                location.reload();
            }, 2000);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while generating analytics');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// View and Export functions
function viewAnalytic(id) {
    window.location.href = `/hms/analytics/view/${id}`;
}

function exportAnalytic(id) {
    window.location.href = `/hms/analytics/export/${id}`;
}

function exportDashboard() {
    alert('Exporting dashboard data...');
}

// Load predictions on page load
document.addEventListener('DOMContentLoaded', function() {
    loadRevenuePredictions();
    loadPatientPredictions();
});
</script>
</x-app-layout>
