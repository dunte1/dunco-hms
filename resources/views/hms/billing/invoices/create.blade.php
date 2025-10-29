<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fa fa-file-invoice text-emerald-600 mr-3"></i>
                            Create New Invoice
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Generate a professional invoice for patient billing</p>
                    </div>
                    <a href="{{ route('hms.billing.invoices.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                        <i class="fa fa-arrow-left mr-2"></i> Back to Invoices
                    </a>
                </div>
            </div>

            <!-- Invoice Form -->
            <form 
                action="{{ route('hms.billing.invoices.store') }}" 
                method="POST"
                x-data="invoiceForm()"
                x-init="init()"
                @submit="validateForm"
            >
                        @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Form -->
                    <div class="lg:col-span-2 space-y-6">
                        
                        <!-- Patient & Doctor Information -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fa fa-user-injured text-blue-600 mr-2"></i>
                                Patient & Doctor Information
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Patient Selection -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Patient <span class="text-red-500">*</span>
                                    </label>
                                    <select 
                                        name="patient_id" 
                                        x-model="patientId"
                                        @change="updatePatientInfo"
                                        required
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                    >
                                        <option value="">Select Patient</option>
                                        @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" 
                                                data-email="{{ $patient->email }}" 
                                                data-phone="{{ $patient->phone }}">
                                            {{ $patient->first_name }} {{ $patient->last_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('patient_id')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                    
                                    <!-- Patient Info Display -->
                                    <div x-show="patientEmail || patientPhone" class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-xs">
                                        <p x-show="patientEmail" class="text-gray-700 dark:text-gray-300">
                                            <i class="fa fa-envelope mr-1"></i>
                                            <span x-text="patientEmail"></span>
                                        </p>
                                        <p x-show="patientPhone" class="text-gray-700 dark:text-gray-300 mt-1">
                                            <i class="fa fa-phone mr-1"></i>
                                            <span x-text="patientPhone"></span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Doctor Selection -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Attending Doctor
                                    </label>
                                    <select 
                                        name="doctor_id"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                    >
                                        <option value="">Select Doctor (Optional)</option>
                                        @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">
                                            Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Dates -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Invoice Date <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="date" 
                                        name="invoice_date" 
                                        x-model="invoiceDate"
                                        required
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                    >
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Due Date <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="date" 
                                        name="due_date" 
                                        x-model="dueDate"
                                        required
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Items -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <i class="fa fa-list text-emerald-600 mr-2"></i>
                                    Invoice Items
                                </h3>
                                <button 
                                    type="button"
                                    @click="addItem"
                                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition text-sm"
                                >
                                    <i class="fa fa-plus mr-2"></i> Add Item
                                </button>
                            </div>

                            <!-- Quick Services -->
                            <div class="mb-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fa fa-bolt text-yellow-500 mr-1"></i>
                                    Quick Add Service
                                </label>
                                <div class="flex gap-2">
                                    <select 
                                        x-model="selectedService"
                                        class="flex-1 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                        <option value="">Select a service...</option>
                                        @foreach($services as $service)
                                        <option value="{{ json_encode($service) }}">
                                            {{ $service['name'] }} - KSh {{ number_format($service['price'], 2) }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <button 
                                        type="button"
                                        @click="addQuickService"
                                        :disabled="!selectedService"
                                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white rounded-lg transition"
                                    >
                                        Add
                                    </button>
                                </div>
                            </div>

                            <!-- Items Table -->
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Description</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase w-24">Qty</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase w-32">Unit Price</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase w-32">Total</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase w-20">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                        <template x-for="(item, index) in items" :key="index">
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                                <td class="px-4 py-3">
                                                    <input 
                                                        type="text" 
                                                        :name="`items[${index}][description]`"
                                                        x-model="item.description"
                                                        @input="calculateItemTotal(index)"
                                                        required
                                                        placeholder="Item description..."
                                                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                                    >
                                                    <input type="hidden" :name="`items[${index}][item_type]`" value="service">
                                                </td>
                                                <td class="px-4 py-3">
                                                    <input 
                                                        type="number" 
                                                        :name="`items[${index}][quantity]`"
                                                        x-model="item.quantity"
                                                        @input="calculateItemTotal(index)"
                                                        min="1"
                                                        step="1"
                                                        required
                                                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                                    >
                                                </td>
                                                <td class="px-4 py-3">
                                                    <input 
                                                        type="number" 
                                                        :name="`items[${index}][unit_price]`"
                                                        x-model="item.unit_price"
                                                        @input="calculateItemTotal(index)"
                                                        min="0"
                                                        step="0.01"
                                                        required
                                                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                                    >
                                                </td>
                                                <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">
                                                    KSh <span x-text="formatNumber(item.total)"></span>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <button 
                                                        type="button"
                                                        @click="removeItem(index)"
                                                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                                        title="Remove item"
                                                    >
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>

                                <!-- Empty State -->
                                <div x-show="items.length === 0" class="text-center py-12">
                                    <i class="fa fa-inbox text-gray-400 text-4xl mb-3"></i>
                                    <p class="text-gray-500 dark:text-gray-400">No items added yet. Click "Add Item" or use Quick Add Service.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fa fa-sticky-note text-yellow-600 mr-2"></i>
                                Additional Notes
                            </label>
                            <textarea 
                                name="notes" 
                                rows="3"
                                placeholder="Add any additional notes or payment terms..."
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                            ></textarea>
                        </div>

                    </div>

                    <!-- Summary Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-xl shadow-lg p-6 sticky top-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fa fa-calculator text-emerald-600 mr-2"></i>
                                Invoice Summary
                            </h3>

                            <!-- Summary Details -->
                            <div class="space-y-3">
                                <!-- Subtotal -->
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Subtotal:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">
                                        KSh <span x-text="formatNumber(subtotal)"></span>
                                    </span>
                                </div>

                                <!-- Tax -->
                                <div class="border-t border-gray-300 dark:border-gray-600 pt-3">
                                    <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">
                                        Tax Amount (KSh):
                                    </label>
                                    <input 
                                        type="number" 
                                        name="tax_amount" 
                                        x-model="taxAmount"
                                        @input="calculateTotal"
                                        min="0"
                                        step="0.01"
                                        value="0"
                                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                    >
                                </div>

                                <!-- Discount -->
                                <div>
                                    <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">
                                        Discount (KSh):
                                    </label>
                                    <input 
                                        type="number" 
                                        name="discount_amount" 
                                        x-model="discountAmount"
                                        @input="calculateTotal"
                                        min="0"
                                        step="0.01"
                                        value="0"
                                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                    >
                                </div>

                                <!-- Total -->
                                <div class="border-t-2 border-emerald-600 pt-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-bold text-gray-900 dark:text-white">TOTAL:</span>
                                        <span class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                                            KSh <span x-text="formatNumber(total)"></span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Hidden field for subtotal -->
                                <input type="hidden" name="subtotal" :value="subtotal">
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-6 space-y-3">
                                <button 
                                    type="submit"
                                    :disabled="items.length === 0 || !patientId"
                                    class="w-full px-6 py-3 bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-semibold rounded-lg transition shadow-lg hover:shadow-xl"
                                >
                                    <i class="fa fa-check-circle mr-2"></i>
                                    Create Invoice
                                </button>
                                <a 
                                    href="{{ route('hms.billing.invoices.index') }}"
                                    class="block w-full px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white text-center font-semibold rounded-lg transition"
                                >
                                    <i class="fa fa-times-circle mr-2"></i>
                                    Cancel
                                </a>
                            </div>

                            <!-- Validation Info -->
                            <div class="mt-4 p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg text-xs text-gray-700 dark:text-gray-300">
                                <p class="font-semibold mb-1">
                                    <i class="fa fa-info-circle text-blue-600 mr-1"></i>
                                    Required:
                                </p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li :class="patientId ? 'line-through text-gray-500' : ''">Select a patient</li>
                                    <li :class="items.length > 0 ? 'line-through text-gray-500' : ''">Add at least one item</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function invoiceForm() {
            return {
                // Patient Info
                patientId: '',
                patientEmail: '',
                patientPhone: '',
                
                // Dates
                invoiceDate: '{{ date("Y-m-d") }}',
                dueDate: '{{ date("Y-m-d", strtotime("+30 days")) }}',
                
                // Items
                items: [],
                selectedService: '',
                
                // Totals
                subtotal: 0,
                taxAmount: 0,
                discountAmount: 0,
                total: 0,
                
                init() {
                    this.addItem(); // Start with one empty item
                },
                
                updatePatientInfo() {
                    const select = event.target;
                    const option = select.options[select.selectedIndex];
                    this.patientEmail = option.dataset.email || '';
                    this.patientPhone = option.dataset.phone || '';
                },
                
                addItem() {
                    this.items.push({
                        description: '',
                        quantity: 1,
                        unit_price: 0,
                        total: 0
                    });
                },
                
                removeItem(index) {
                    if (confirm('Remove this item?')) {
                        this.items.splice(index, 1);
                        this.calculateTotal();
                    }
                },
                
                addQuickService() {
                    if (!this.selectedService) return;
                    
                    const service = JSON.parse(this.selectedService);
                    this.items.push({
                        description: service.name,
                        quantity: 1,
                        unit_price: service.price,
                        total: service.price
                    });
                    
                    this.selectedService = '';
                    this.calculateTotal();
                },
                
                calculateItemTotal(index) {
                    const item = this.items[index];
                    item.total = (parseFloat(item.quantity) || 0) * (parseFloat(item.unit_price) || 0);
                    this.calculateTotal();
                },
                
                calculateTotal() {
                    this.subtotal = this.items.reduce((sum, item) => sum + (item.total || 0), 0);
                    this.total = this.subtotal + (parseFloat(this.taxAmount) || 0) - (parseFloat(this.discountAmount) || 0);
                },
                
                formatNumber(num) {
                    return parseFloat(num || 0).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                },
                
                validateForm(e) {
                    if (this.items.length === 0) {
                        e.preventDefault();
                        alert('Please add at least one item to the invoice.');
                        return false;
                    }
                    
                    if (!this.patientId) {
                        e.preventDefault();
                        alert('Please select a patient.');
                        return false;
                    }
                }
            }
        }
    </script>
</x-app-layout>
