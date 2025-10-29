<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.hr.payrolls.index') }}" class="hover:text-cyan-600">Payrolls</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Generate Payroll</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-money-bill-wave text-cyan-600 mr-3"></i>
                    Generate Employee Payroll
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Calculate and generate salary for an employee</p>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 h-2"></div>
                
                <form method="POST" action="{{ route('hms.hr.payrolls.store') }}" class="p-6 space-y-6" x-data="payrollForm()">
                    @csrf

                    <!-- Employee & Period -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Employee & Pay Period</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Employee <span class="text-red-500">*</span>
                                </label>
                                <select name="employee_id" required x-model="selectedEmployee" @change="updateBasicSalary()"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-cyan-500 focus:border-cyan-500">
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" data-salary="{{ $employee->salary }}">
                                            {{ $employee->first_name }} {{ $employee->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Payroll Period <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="payroll_period" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-cyan-500 focus:border-cyan-500"
                                    placeholder="e.g., January 2025, Week 1 March 2025">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Pay Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="pay_date" value="{{ date('Y-m-d') }}" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-cyan-500 focus:border-cyan-500">
                            </div>
                        </div>
                    </div>

                    <!-- Salary Components -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Salary Components</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Basic Salary <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                                    <input type="number" name="basic_salary" x-model.number="basicSalary" @input="calculateTotals()" step="0.01" min="0" required
                                        class="w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-cyan-500 focus:border-cyan-500"
                                        placeholder="0.00">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Overtime Pay
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                                    <input type="number" name="overtime_pay" x-model.number="overtimePay" @input="calculateTotals()" step="0.01" min="0"
                                        class="w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-cyan-500 focus:border-cyan-500"
                                        placeholder="0.00">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Bonus
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                                    <input type="number" name="bonus" x-model.number="bonus" @input="calculateTotals()" step="0.01" min="0"
                                        class="w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-cyan-500 focus:border-cyan-500"
                                        placeholder="0.00">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Allowances
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                                    <input type="number" name="allowances" x-model.number="allowances" @input="calculateTotals()" step="0.01" min="0"
                                        class="w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-cyan-500 focus:border-cyan-500"
                                        placeholder="0.00">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Deductions
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                                    <input type="number" name="deductions" x-model.number="deductions" @input="calculateTotals()" step="0.01" min="0"
                                        class="w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-cyan-500 focus:border-cyan-500"
                                        placeholder="0.00">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Summary</h3>
                        <div class="bg-cyan-50 dark:bg-cyan-900/20 rounded-lg p-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Gross Salary</p>
                                    <p class="text-2xl font-bold text-cyan-600 dark:text-cyan-400" x-text="'$' + grossSalary.toFixed(2)"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Net Salary (After Deductions)</p>
                                    <p class="text-2xl font-bold text-green-600 dark:text-green-400" x-text="'$' + netSalary.toFixed(2)"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Notes
                        </label>
                        <textarea name="notes" rows="3"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-cyan-500 focus:border-cyan-500"
                            placeholder="Additional notes or comments..."></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="flex-1 px-6 py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fa fa-save mr-2"></i> Generate Payroll
                        </button>
                        <a href="{{ route('hms.hr.payrolls.index') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fa fa-times mr-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function payrollForm() {
            return {
                selectedEmployee: '',
                basicSalary: 0,
                overtimePay: 0,
                bonus: 0,
                allowances: 0,
                deductions: 0,
                grossSalary: 0,
                netSalary: 0,
                updateBasicSalary() {
                    if (this.selectedEmployee) {
                        const select = document.querySelector('select[name="employee_id"]');
                        const option = select.options[select.selectedIndex];
                        this.basicSalary = parseFloat(option.dataset.salary) || 0;
                        this.calculateTotals();
                    }
                },
                calculateTotals() {
                    this.grossSalary = this.basicSalary + this.overtimePay + this.bonus + this.allowances;
                    this.netSalary = this.grossSalary - this.deductions;
                }
            }
        }
    </script>
</x-app-layout>

