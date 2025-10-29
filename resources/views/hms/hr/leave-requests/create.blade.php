<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.hr.leave-requests.index') }}" class="hover:text-purple-600">Leave Requests</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Submit Request</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-calendar-minus text-purple-600 mr-3"></i>
                    Submit Leave Request
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Request time off for an employee</p>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2"></div>
                
                <form method="POST" action="{{ route('hms.hr.leave-requests.store') }}" class="p-6 space-y-6" x-data="leaveRequestForm()">
                    @csrf

                    <!-- Employee Selection -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Employee Information</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Employee <span class="text-red-500">*</span>
                            </label>
                            <select name="employee_id" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ $employee->first_name }} {{ $employee->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Leave Details -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Leave Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Leave Type <span class="text-red-500">*</span>
                                </label>
                                <select name="leave_type" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                                    <option value="">Select Leave Type</option>
                                    <option value="sick">Sick Leave</option>
                                    <option value="vacation">Vacation Leave</option>
                                    <option value="personal">Personal Leave</option>
                                    <option value="maternity">Maternity Leave</option>
                                    <option value="emergency">Emergency Leave</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Start Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="start_date" x-model="startDate" @change="calculateDays()" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    End Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="end_date" x-model="endDate" @change="calculateDays()" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                            </div>

                            <div class="md:col-span-2">
                                <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        <i class="fa fa-calendar text-purple-600 mr-2"></i>
                                        Total Days: <span class="font-bold text-purple-600 dark:text-purple-400" x-text="totalDays"></span>
                                    </p>
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Reason for Leave <span class="text-red-500">*</span>
                                </label>
                                <textarea name="reason" rows="4" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500"
                                    placeholder="Please provide detailed reason for leave request..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Templates -->
                    <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">
                            <i class="fa fa-bolt text-purple-600 mr-2"></i> Quick Reasons
                        </h4>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            <button type="button" onclick="fillReason('sick')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                Medical/Sick
                            </button>
                            <button type="button" onclick="fillReason('family')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                Family Emergency
                            </button>
                            <button type="button" onclick="fillReason('personal')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                Personal Matter
                            </button>
                            <button type="button" onclick="fillReason('vacation')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                Annual Vacation
                            </button>
                            <button type="button" onclick="fillReason('appointment')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                Medical Appointment
                            </button>
                            <button type="button" onclick="fillReason('bereavement')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                Bereavement
                            </button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="flex-1 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fa fa-paper-plane mr-2"></i> Submit Leave Request
                        </button>
                        <a href="{{ route('hms.hr.leave-requests.index') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fa fa-times mr-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function leaveRequestForm() {
            return {
                startDate: '',
                endDate: '',
                totalDays: 0,
                calculateDays() {
                    if (this.startDate && this.endDate) {
                        const start = new Date(this.startDate);
                        const end = new Date(this.endDate);
                        const diffTime = Math.abs(end - start);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                        this.totalDays = diffDays > 0 ? diffDays : 0;
                    } else {
                        this.totalDays = 0;
                    }
                }
            }
        }

        function fillReason(type) {
            const reasons = {
                sick: "I am requesting sick leave due to medical reasons. I need time off to recover and will provide medical certificate if required.",
                family: "I need to attend to an urgent family matter that requires my immediate attention and presence.",
                personal: "I am requesting personal leave for personal matters that require my attention during this period.",
                vacation: "I am requesting annual vacation leave. I have made arrangements to ensure my responsibilities are covered during my absence.",
                appointment: "I have scheduled medical appointments that require time off from work. I will provide documentation upon request.",
                bereavement: "I am requesting bereavement leave due to the passing of a family member. I need time to attend funeral arrangements and be with family."
            };
            
            const reasonField = document.querySelector('textarea[name="reason"]');
            if (reasonField && reasons[type]) {
                reasonField.value = reasons[type];
            }
        }
    </script>
</x-app-layout>

