<x-app-layout>
    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.referrals.index') }}" class="hover:text-indigo-600">Referrals</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>{{ $referral->referral_number }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-exchange-alt text-indigo-600 mr-3"></i>
                        Referral Details
                    </h1>
                </div>
                <div class="flex items-center gap-2">
                    @if($referral->status === 'pending')
                        <form action="{{ route('hms.referrals.accept', $referral) }}" method="POST" class="inline">
                            @csrf
                            <button class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg" onclick="return confirm('Accept this referral?')">
                                <i class="fa fa-check mr-1"></i> Accept
                            </button>
                        </form>
                        <form action="{{ route('hms.referrals.reject', $referral) }}" method="POST" class="inline">
                            @csrf
                            <button class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg" onclick="return confirm('Reject this referral?')">
                                <i class="fa fa-times mr-1"></i> Reject
                            </button>
                        </form>
                    @endif

                    @if($referral->status === 'accepted')
                        <form action="{{ route('hms.referrals.complete', $referral) }}" method="POST" class="inline">
                            @csrf
                            <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg" onclick="return confirm('Mark this referral as completed?')">
                                <i class="fa fa-check-double mr-1"></i> Complete
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('hms.referrals.edit', $referral) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                        <i class="fa fa-edit mr-1"></i> Edit
                    </a>
                    <a href="{{ route('hms.referrals.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                        <i class="fa fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center" role="alert">
                    <i class="fa fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Referral Overview -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Referral Information</h3>
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 text-sm font-semibold rounded {{ $referral->referral_type === 'in' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                    <i class="fa fa-{{ $referral->referral_type === 'in' ? 'sign-in-alt' : 'sign-out-alt' }} mr-1"></i>{{ ucfirst($referral->referral_type) }} Referral
                                </span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Referral Number:</span>
                                <span class="ml-2 font-medium text-gray-900 dark:text-white">{{ $referral->referral_number }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Date:</span>
                                <span class="ml-2 font-medium text-gray-900 dark:text-white">{{ $referral->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Urgency:</span>
                                <span class="ml-2 px-2 py-1 text-xs font-medium rounded {{ $referral->urgency === 'emergency' ? 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' : ($referral->urgency === 'urgent' ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200' : 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200') }}">
                                    {{ ucfirst($referral->urgency) }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Status:</span>
                                <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full {{ $referral->status === 'completed' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : ($referral->status === 'accepted' ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200' : ($referral->status === 'rejected' ? 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' : 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200')) }}">
                                    {{ ucfirst($referral->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Facility Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-hospital text-indigo-600 mr-2"></i> Facility Information
                        </h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Referring Facility:</span>
                                <p class="mt-1 font-medium text-gray-900 dark:text-white">{{ $referral->referring_facility }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Receiving Facility:</span>
                                <p class="mt-1 font-medium text-gray-900 dark:text-white">{{ $referral->receiving_facility }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Doctor Information -->
                    @if($referral->referringDoctor || $referral->receivingDoctor)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-user-md text-indigo-600 mr-2"></i> Doctor Information
                        </h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            @if($referral->referringDoctor)
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Referring Doctor:</span>
                                <p class="mt-1 font-medium text-gray-900 dark:text-white">Dr. {{ $referral->referringDoctor->first_name }} {{ $referral->referringDoctor->last_name }}</p>
                            </div>
                            @endif
                            @if($referral->receivingDoctor)
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Receiving Doctor:</span>
                                <p class="mt-1 font-medium text-gray-900 dark:text-white">Dr. {{ $referral->receivingDoctor->first_name }} {{ $referral->receivingDoctor->last_name }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Clinical Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-notes-medical text-indigo-600 mr-2"></i> Clinical Information
                        </h3>
                        <div class="space-y-4 text-sm">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Reason for Referral:</span>
                                <p class="mt-1 text-gray-900 dark:text-white">{{ $referral->reason }}</p>
                            </div>
                            @if($referral->clinical_summary)
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Clinical Summary:</span>
                                <p class="mt-1 text-gray-900 dark:text-white whitespace-pre-line">{{ $referral->clinical_summary }}</p>
                            </div>
                            @endif
                            @if($referral->investigations_done)
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Investigations Done:</span>
                                <p class="mt-1 text-gray-900 dark:text-white whitespace-pre-line">{{ $referral->investigations_done }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Patient Info -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            <i class="fa fa-user-injured text-indigo-600 mr-2"></i> Patient Info
                        </h3>
                        <div class="space-y-3">
                            <p class="font-medium text-gray-900 dark:text-white text-lg">{{ $referral->patient->first_name ?? '' }} {{ $referral->patient->last_name ?? '' }}</p>
                            @if($referral->patient->patient_no)
                                <p class="text-sm text-gray-600 dark:text-gray-400"><i class="fa fa-id-card mr-1"></i> {{ $referral->patient->patient_no }}</p>
                            @endif
                            @if($referral->patient->phone)
                                <p class="text-sm text-gray-600 dark:text-gray-400"><i class="fa fa-phone mr-1"></i> {{ $referral->patient->phone }}</p>
                            @endif
                            @if($referral->patient->email)
                                <p class="text-sm text-gray-600 dark:text-gray-400"><i class="fa fa-envelope mr-1"></i> {{ $referral->patient->email }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Related Visits -->
                    @if($referral->opdVisit || $referral->ipdAdmission)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            <i class="fa fa-link text-indigo-600 mr-2"></i> Related Visits
                        </h3>
                        <div class="space-y-3 text-sm">
                            @if($referral->opdVisit)
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <span class="text-gray-500 dark:text-gray-400">OPD Visit:</span>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $referral->opdVisit->visit_number }}</p>
                            </div>
                            @endif
                            @if($referral->ipdAdmission)
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <span class="text-gray-500 dark:text-gray-400">IPD Admission:</span>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $referral->ipdAdmission->admission_number }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Quick Status Info -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            <i class="fa fa-info-circle text-indigo-600 mr-2"></i> Quick Info
                        </h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <span class="text-gray-600 dark:text-gray-400">Created</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $referral->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <span class="text-gray-600 dark:text-gray-400">Last Updated</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $referral->updated_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
