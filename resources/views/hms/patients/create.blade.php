<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-user-plus text-blue-600 mr-3"></i>
                        Register New Patient
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Fill in the patient details below</p>
                </div>
                <a href="{{ route('hms.patients.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                    <i class="fa fa-arrow-left mr-2"></i> Back to List
                </a>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
                <form action="{{ route('hms.patients.store') }}" method="POST">
                    @csrf

                    <!-- Personal Information Section -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-user text-blue-600 mr-2"></i> Personal Information
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Patient Number -->
                            <div>
                                <label for="patient_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Patient Number <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="patient_no" 
                                       id="patient_no" 
                                       value="{{ old('patient_no', 'PAT-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT)) }}"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 @error('patient_no') border-red-500 @enderror"
                                       required>
                                @error('patient_no')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- First Name -->
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    First Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="first_name" 
                                       id="first_name" 
                                       value="{{ old('first_name') }}"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 @error('first_name') border-red-500 @enderror"
                                       required>
                                @error('first_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Last Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="last_name" 
                                       id="last_name" 
                                       value="{{ old('last_name') }}"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 @error('last_name') border-red-500 @enderror"
                                       required>
                                @error('last_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Gender
                                </label>
                                <select name="gender" 
                                        id="gender"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 @error('gender') border-red-500 @enderror">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <label for="dob" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Date of Birth
                                </label>
                                <input type="date" 
                                       name="dob" 
                                       id="dob" 
                                       value="{{ old('dob') }}"
                                       max="{{ date('Y-m-d') }}"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 @error('dob') border-red-500 @enderror">
                                @error('dob')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-address-book text-blue-600 mr-2"></i> Contact Information
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Email Address
                                </label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       value="{{ old('email') }}"
                                       placeholder="patient@example.com"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Phone Number
                                </label>
                                <input type="tel" 
                                       name="phone" 
                                       id="phone" 
                                       value="{{ old('phone') }}"
                                       placeholder="+1 (555) 123-4567"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Address
                                </label>
                                <textarea name="address" 
                                          id="address" 
                                          rows="3"
                                          placeholder="Street address, city, state, zip code"
                                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Insurance Information Section -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-shield-alt text-green-600 mr-2"></i> Insurance Information
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Has Insurance -->
                            <div class="md:col-span-2">
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="has_insurance" 
                                           id="has_insurance" 
                                           value="1"
                                           {{ old('has_insurance') ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2"
                                           onchange="toggleInsuranceFields()">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Patient has insurance (NHIF, SHA, or other)
                                    </span>
                                </label>
                            </div>

                            <!-- Insurance Provider -->
                            <div id="insuranceFields" style="display: none;" class="md:col-span-2">
                                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                                    <p class="text-sm text-blue-800 dark:text-blue-200 mb-4">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        <strong>Biometric Enrollment Recommended:</strong> Patients with insurance (NHIF, SHA, etc.) should enroll biometric data for secure identification and faster check-in.
                                    </p>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label for="insurance_provider_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Insurance Provider
                                            </label>
                                            <select name="insurance_provider_id" 
                                                    id="insurance_provider_id"
                                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Select Provider</option>
                                                <option value="nhif">NHIF (National Hospital Insurance Fund)</option>
                                                <option value="sha">SHA (Social Health Authority)</option>
                                                <option value="private">Private Insurance</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label for="insurance_policy_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Policy/Member Number
                                            </label>
                                            <input type="text" 
                                                   name="insurance_policy_number" 
                                                   id="insurance_policy_number"
                                                   value="{{ old('insurance_policy_number') }}"
                                                   placeholder="Enter policy/member number"
                                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>

                                    <!-- Biometric Enrollment Option -->
                                    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded border border-yellow-200 dark:border-yellow-800">
                                        <label class="flex items-start">
                                            <input type="checkbox" 
                                                   name="enroll_biometric" 
                                                   id="enroll_biometric" 
                                                   value="1"
                                                   {{ old('enroll_biometric') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2 mt-1">
                                            <div class="flex-1">
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 block">
                                                    <i class="fas fa-fingerprint text-blue-600 mr-1"></i>
                                                    Enroll Biometric Data (Recommended for Insurance Patients)
                                                </span>
                                                <span class="text-xs text-gray-600 dark:text-gray-400 mt-1 block">
                                                    Enrolling biometrics will allow secure patient identification and faster check-in at the hospital. You'll be redirected to biometric enrollment after registration.
                                                </span>
                                            </div>
                                        </label>
                                        
                                        <div class="mt-3 pt-3 border-t border-yellow-300 dark:border-yellow-700">
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">
                                                <i class="fas fa-link mr-1"></i>
                                                <strong>Direct Link:</strong> After registration, access biometric enrollment at:
                                            </p>
                                            <code class="text-xs bg-white dark:bg-gray-800 px-2 py-1 rounded block break-all">
                                                http://127.0.0.1:8001/hms/security/biometric?patient_id={patient_id}
                                            </code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('hms.patients.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                            <i class="fa fa-times mr-2"></i> Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                            <i class="fa fa-save mr-2"></i> Register Patient
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleInsuranceFields() {
            const checkbox = document.getElementById('has_insurance');
            const fields = document.getElementById('insuranceFields');
            
            if (checkbox.checked) {
                fields.style.display = 'block';
            } else {
                fields.style.display = 'none';
                document.getElementById('enroll_biometric').checked = false;
            }
        }

        // Check on page load if checkbox was previously checked
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('has_insurance').checked) {
                toggleInsuranceFields();
            }
        });
    </script>
</x-app-layout>
