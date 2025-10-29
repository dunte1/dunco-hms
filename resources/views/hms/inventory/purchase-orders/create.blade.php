<x-app-layout>
    <div class="py-6" x-data="purchaseOrderForm()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fa fa-file-invoice text-purple-600 mr-3"></i>
                            Create Purchase Order
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Create a new purchase order for inventory items
                        </p>
                    </div>
                    <a href="{{ route('hms.inventory.purchase-orders.index') }}" 
                       class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition flex items-center">
                        <i class="fa fa-arrow-left mr-2"></i>
                        Back to List
                    </a>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('hms.inventory.purchase-orders.store') }}" @submit="validateForm">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Form -->
                    <div class="lg:col-span-2 space-y-6">
                        
                        <!-- Basic Information -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                            <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-4">
                                <h3 class="text-lg font-bold text-white flex items-center">
                                    <i class="fa fa-info-circle mr-2"></i>
                                    Basic Information
                                </h3>
                            </div>

                            <div class="p-6 space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- PO Number -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            PO Number <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="po_number" value="{{ old('po_number', $poNumber) }}"
                                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                               readonly required>
                                        @error('po_number')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Supplier -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            Supplier <span class="text-red-500">*</span>
                                        </label>
                                        <select name="supplier_id" x-model="supplierId" @change="updateSupplierInfo"
                                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                                required>
                                            <option value="">Select Supplier</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}" 
                                                        data-email="{{ $supplier->email }}"
                                                        data-phone="{{ $supplier->phone }}"
                                                        data-address="{{ $supplier->address }}"
                                                        {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('supplier_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Order Date -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            Order Date <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}"
                                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                               required>
                                        @error('order_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Expected Delivery Date -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            Expected Delivery Date
                                        </label>
                                        <input type="date" name="expected_delivery_date" value="{{ old('expected_delivery_date') }}"
                                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                        @error('expected_delivery_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Reference Number -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            Reference Number
                                        </label>
                                        <input type="text" name="reference_number" value="{{ old('reference_number') }}"
                                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                               placeholder="e.g., RFQ-12345">
                                        @error('reference_number')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Notes
                                    </label>
                                    <textarea name="notes" rows="2"
                                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                              placeholder="Add any additional notes...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Items Section -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-bold text-white flex items-center">
                                        <i class="fa fa-boxes mr-2"></i>
                                        Order Items
                                    </h3>
                                    <button type="button" @click="addItem"
                                            class="px-4 py-2 bg-white hover:bg-gray-100 text-blue-600 rounded-lg transition text-sm font-semibold flex items-center">
                                        <i class="fa fa-plus mr-2"></i>
                                        Add Item
                                    </button>
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="space-y-4" id="items-container">
                                    <template x-for="(item, index) in items" :key="index">
                                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-700">
                                            <div class="flex items-center justify-between mb-3">
                                                <h4 class="font-semibold text-gray-900 dark:text-white">
                                                    Item <span x-text="index + 1"></span>
                                                </h4>
                                                <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                                        class="text-red-600 hover:text-red-700 text-sm">
                                                    <i class="fa fa-trash"></i> Remove
                                                </button>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                                <!-- Medicine (Optional) -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                        Select Medicine (Optional)
                                                    </label>
                                                    <select :name="'items[' + index + '][medicine_id]'" 
                                                            x-model="item.medicine_id"
                                                            @change="selectMedicine(index)"
                                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm">
                                                        <option value="">-- Select Medicine --</option>
                                                        @foreach($medicines as $medicine)
                                                            <option value="{{ $medicine->id }}"
                                                                    data-name="{{ $medicine->name }}"
                                                                    data-form="{{ $medicine->dosage_form }}"
                                                                    data-strength="{{ $medicine->strength }}"
                                                                    data-price="{{ $medicine->unit_price }}">
                                                                {{ $medicine->name }} - {{ $medicine->dosage_form }} {{ $medicine->strength }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Item Name -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                        Item Name <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="text" :name="'items[' + index + '][item_name]'" 
                                                           x-model="item.item_name"
                                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm"
                                                           required>
                                                </div>

                                                <!-- Item Code -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                        Item Code
                                                    </label>
                                                    <input type="text" :name="'items[' + index + '][item_code]'" 
                                                           x-model="item.item_code"
                                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm">
                                                </div>

                                                <!-- Description -->
                                                <div class="md:col-span-2 lg:col-span-3">
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                        Description
                                                    </label>
                                                    <input type="text" :name="'items[' + index + '][description]'" 
                                                           x-model="item.description"
                                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm"
                                                           placeholder="Additional item details">
                                                </div>

                                                <!-- Quantity -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                        Quantity <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="number" :name="'items[' + index + '][quantity]'" 
                                                           x-model.number="item.quantity"
                                                           @input="calculateItemTotal(index)"
                                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm"
                                                           min="1" required>
                                                </div>

                                                <!-- Unit Price -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                        Unit Price <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="number" :name="'items[' + index + '][unit_price]'" 
                                                           x-model.number="item.unit_price"
                                                           @input="calculateItemTotal(index)"
                                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm"
                                                           step="0.01" min="0" required>
                                                </div>

                                                <!-- Total Price -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                        Total Price
                                                    </label>
                                                    <input type="number" :name="'items[' + index + '][total_price]'" 
                                                           x-model.number="item.total_price"
                                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-gray-100 text-sm"
                                                           readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <template x-if="items.length === 0">
                                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                        <i class="fa fa-box-open text-4xl mb-2"></i>
                                        <p>No items added. Click "Add Item" to start.</p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Terms & Conditions -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 p-4">
                                <h3 class="text-lg font-bold text-white flex items-center">
                                    <i class="fa fa-file-contract mr-2"></i>
                                    Terms & Conditions
                                </h3>
                            </div>

                            <div class="p-6">
                                <textarea name="terms_and_conditions" rows="4"
                                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                          placeholder="Enter payment terms, delivery conditions, warranties, etc.">{{ old('terms_and_conditions') }}</textarea>
                                @error('terms_and_conditions')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Summary Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden sticky top-6">
                            <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-4">
                                <h3 class="text-lg font-bold text-white flex items-center">
                                    <i class="fa fa-calculator mr-2"></i>
                                    Order Summary
                                </h3>
                            </div>

                            <div class="p-6 space-y-4">
                                <!-- Supplier Info -->
                                <div x-show="supplierId" class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Supplier Details</h4>
                                    <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                        <p x-show="supplierEmail"><i class="fa fa-envelope w-4"></i> <span x-text="supplierEmail"></span></p>
                                        <p x-show="supplierPhone"><i class="fa fa-phone w-4"></i> <span x-text="supplierPhone"></span></p>
                                    </div>
                                </div>

                                <!-- Calculations -->
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                                        <span class="font-semibold text-gray-900 dark:text-white" x-text="'KSh ' + subtotal.toLocaleString('en-KE', {minimumFractionDigits: 2})"></span>
                                    </div>

                                    <!-- Tax -->
                                    <div>
                                        <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Tax Amount:</label>
                                        <input type="number" name="tax_amount" x-model.number="taxAmount" @input="calculateTotal"
                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm"
                                               step="0.01" min="0" placeholder="0.00">
                                    </div>

                                    <!-- Discount -->
                                    <div>
                                        <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Discount Amount:</label>
                                        <input type="number" name="discount_amount" x-model.number="discountAmount" @input="calculateTotal"
                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm"
                                               step="0.01" min="0" placeholder="0.00">
                                    </div>

                                    <!-- Shipping -->
                                    <div>
                                        <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Shipping Cost:</label>
                                        <input type="number" name="shipping_cost" x-model.number="shippingCost" @input="calculateTotal"
                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm"
                                               step="0.01" min="0" placeholder="0.00">
                                    </div>

                                    <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-lg font-bold text-gray-900 dark:text-white">Total:</span>
                                            <span class="text-2xl font-bold text-green-600" x-text="'KSh ' + total.toLocaleString('en-KE', {minimumFractionDigits: 2})"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="space-y-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <button type="submit" name="status" value="draft"
                                            class="w-full px-4 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition font-semibold flex items-center justify-center">
                                        <i class="fa fa-save mr-2"></i>
                                        Save as Draft
                                    </button>
                                    <button type="submit" name="status" value="submitted"
                                            class="w-full px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-lg transition font-semibold flex items-center justify-center">
                                        <i class="fa fa-paper-plane mr-2"></i>
                                        Submit for Approval
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function purchaseOrderForm() {
            return {
                items: [{
                    medicine_id: '',
                    item_name: '',
                    item_code: '',
                    description: '',
                    quantity: 1,
                    unit_price: 0,
                    total_price: 0
                }],
                supplierId: '',
                supplierEmail: '',
                supplierPhone: '',
                supplierAddress: '',
                taxAmount: 0,
                discountAmount: 0,
                shippingCost: 0,
                
                get subtotal() {
                    return this.items.reduce((sum, item) => sum + (parseFloat(item.total_price) || 0), 0);
                },
                
                get total() {
                    return this.subtotal + (parseFloat(this.taxAmount) || 0) - (parseFloat(this.discountAmount) || 0) + (parseFloat(this.shippingCost) || 0);
                },
                
                addItem() {
                    this.items.push({
                        medicine_id: '',
                        item_name: '',
                        item_code: '',
                        description: '',
                        quantity: 1,
                        unit_price: 0,
                        total_price: 0
                    });
                },
                
                removeItem(index) {
                    this.items.splice(index, 1);
                },
                
                calculateItemTotal(index) {
                    const item = this.items[index];
                    item.total_price = (parseFloat(item.quantity) || 0) * (parseFloat(item.unit_price) || 0);
                },
                
                calculateTotal() {
                    // This is reactive through Alpine.js
                },
                
                selectMedicine(index) {
                    const select = event.target;
                    const option = select.options[select.selectedIndex];
                    
                    if (option.value) {
                        this.items[index].item_name = option.dataset.name + ' - ' + option.dataset.form + ' ' + option.dataset.strength;
                        this.items[index].unit_price = parseFloat(option.dataset.price) || 0;
                        this.calculateItemTotal(index);
                    }
                },
                
                updateSupplierInfo() {
                    const select = event.target;
                    const option = select.options[select.selectedIndex];
                    
                    if (option.value) {
                        this.supplierEmail = option.dataset.email;
                        this.supplierPhone = option.dataset.phone;
                        this.supplierAddress = option.dataset.address;
                    } else {
                        this.supplierEmail = '';
                        this.supplierPhone = '';
                        this.supplierAddress = '';
                    }
                },
                
                validateForm(e) {
                    if (this.items.length === 0) {
                        e.preventDefault();
                        alert('Please add at least one item to the purchase order.');
                        return false;
                    }
                    return true;
                }
            }
        }
    </script>
</x-app-layout>

