<div class="sidebar-container bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 text-gray-800 dark:text-white w-64 h-full shadow-xl border-r border-gray-200 dark:border-gray-700" x-data="sidebarNav()">
    
    <!-- Header -->
    <div class="sidebar-header p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-emerald-600 to-teal-600">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-3 shadow-md">
                    @if($themeSettings['hospital_logo'] ?? false)
                        <img src="{{ $themeSettings['hospital_logo'] }}" alt="Hospital Logo" class="w-9 h-9 object-contain">
                    @else
                    <i class="fa fa-hospital text-emerald-600 text-xl"></i>
                    @endif
                </div>
                <div>
                    <h1 class="text-white font-bold text-lg">{{ $themeSettings['hospital_name'] ?? config('app.name', 'DuncoHMS') }}</h1>
                    <p class="text-emerald-100 text-xs">Healthcare System</p>
                </div>
            </div>
            <button @click="$dispatch('toggle-sidebar')" class="p-2 hover:bg-white hover:bg-opacity-20 rounded-lg transition text-white">
                <i class="fa fa-bars text-sm"></i>
            </button>
        </div>
    </div>
    
    <div class="flex flex-col" style="height: calc(100vh - 104px);">
        <nav class="sidebar-nav mt-2 overflow-y-auto flex-1 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600" style="max-height: calc(100% - 60px);">
            <ul class="space-y-0.5 px-3 pb-4 mb-2">
            
            {{-- 🏠 1. DASHBOARD --}}
            @can('view dashboard analytics')
                <li class="mb-1">
                    <div class="menu-item menu-item-emerald" @click="toggleMenu('dashboard', true)">
                        <div class="flex items-center">
                            <div class="menu-icon bg-gradient-to-br from-emerald-500 to-teal-600">
                                <i class="fa fa-home text-white text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">Dashboard</span>
                        </div>
                        <i class="fa fa-chevron-down text-xs transition-transform text-gray-400" 
                           :class="isMenuOpen('dashboard') ? 'rotate-180' : ''"></i>
                    </div>
                    <ul x-show="isMenuOpen('dashboard')" x-transition class="submenu submenu-emerald">
                        <li>
                            <a @click.stop href="{{ route('dashboard') }}" 
                               class="submenu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="fa fa-compass mr-2 w-4"></i>
                                <span>Overview</span>
                            </a>
                        </li>
                        @can('view analytics')
                            <li>
                                <a @click.stop href="{{ route('analytics.bi-dashboard') }}" 
                                   class="submenu-link {{ request()->routeIs('analytics.*') ? 'active' : '' }}">
                                    <i class="fa fa-chart-bar mr-2 w-4"></i>
                                    <span>Analytics</span>
                                </a>
                            </li>
                        @endcan
                        <li>
                            <a @click.stop href="{{ route('hms.dashboard.notifications') }}" 
                               class="submenu-link {{ request()->routeIs('hms.dashboard.notifications') ? 'active' : '' }}">
                                <i class="fa fa-bell mr-2 w-4"></i>
                                <span>Notifications</span>
                            </a>
                        </li>
                        <li>
                            <a @click.stop href="{{ route('hms.dashboard.today-summary') }}" 
                               class="submenu-link {{ request()->routeIs('hms.dashboard.today-summary') ? 'active' : '' }}">
                                <i class="fa fa-calendar-day mr-2 w-4"></i>
                                <span>Today's Summary</span>
                            </a>
                        </li>
                        <li>
                            <a @click.stop href="{{ route('hms.dashboard.active-staff') }}" 
                               class="submenu-link {{ request()->routeIs('hms.dashboard.active-staff') ? 'active' : '' }}">
                                <i class="fa fa-users mr-2 w-4"></i>
                                <span>Active Staff</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan

            <li class="menu-divider"></li>

            {{-- 🏥 2. HOSPITAL MANAGEMENT --}}
            @canany(['view patients', 'add patients', 'edit patients', 'delete patients', 'view doctors', 'manage staff profiles', 'manage nurses', 'manage ambulances'])
                <li class="mb-1">
                    <div class="menu-item menu-item-blue" @click="toggleMenu('hospital-management', true)">
                        <div class="flex items-center">
                            <div class="menu-icon bg-gradient-to-br from-blue-500 to-indigo-600">
                                <i class="fa fa-hospital text-white text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">Hospital Management</span>
                        </div>
                        <i class="fa fa-chevron-down text-xs transition-transform text-gray-400" 
                           :class="isMenuOpen('hospital-management') ? 'rotate-180' : ''"></i>
                    </div>
                    <ul x-show="isMenuOpen('hospital-management')" x-transition class="submenu submenu-blue">
                        
                        {{-- Patients Submenu --}}
                        @canany(['view patients', 'add patients'])
                            <li>
                                <div class="nested-menu-item" @click="toggleMenu('patients')">
                                    <div class="flex items-center text-sm">
                                        <i class="fa fa-user-injured mr-2 w-4 text-blue-600"></i>
                                        <span>Patients</span>
                                    </div>
                                    <i class="fa fa-chevron-down text-xs transition-transform" 
                                       :class="isMenuOpen('patients') ? 'rotate-180' : ''"></i>
                                </div>
                                <ul x-show="isMenuOpen('patients')" x-transition class="nested-submenu">
                                    @can('view patients')
                                        <li>
                                            <a @click.stop href="{{ route('hms.patients.index') }}" 
                                               class="nested-link {{ request()->routeIs('hms.patients.*') ? 'active' : '' }}">
                                                <i class="fa fa-list mr-2 w-4"></i> All Patients
                                            </a>
                                        </li>
                                        <li>
                                            <a @click.stop href="{{ route('hms.ipd.index') }}" 
                                               class="nested-link {{ request()->routeIs('hms.ipd.*') ? 'active' : '' }}">
                                                <i class="fa fa-procedures mr-2 w-4"></i> Admissions (IPD)
                                            </a>
                                        </li>
                                        <li>
                                            <a @click.stop href="{{ route('hms.opd.index') }}" 
                                               class="nested-link {{ request()->routeIs('hms.opd.*') ? 'active' : '' }}">
                                                <i class="fa fa-walking mr-2 w-4"></i> Outpatients (OPD)
                                            </a>
                                        </li>
                                        <li>
                                            <a @click.stop href="{{ route('hms.diagnosis.patient-diagnoses') }}" 
                                               class="nested-link {{ request()->routeIs('hms.diagnosis.patient-diagnoses*') ? 'active' : '' }}">
                                                <i class="fa fa-notes-medical mr-2 w-4"></i> Diagnosis Reports
                                            </a>
                                        </li>
                                        <li>
                                            <a @click.stop href="{{ route('hms.discharge-summary.index') }}" 
                                               class="nested-link {{ request()->routeIs('hms.discharge-summary.*') ? 'active' : '' }}">
                                                <i class="fa fa-file-medical mr-2 w-4"></i> Discharge Summary
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany

                        {{-- Doctors Submenu --}}
                        @canany(['view doctors', 'manage staff profiles'])
                            <li>
                                <div class="nested-menu-item" @click="toggleMenu('doctors')">
                                    <div class="flex items-center text-sm">
                                        <i class="fa fa-user-md mr-2 w-4 text-blue-600"></i>
                                        <span>Doctors</span>
                                    </div>
                                    <i class="fa fa-chevron-down text-xs transition-transform" 
                                       :class="isMenuOpen('doctors') ? 'rotate-180' : ''"></i>
                                </div>
                                <ul x-show="isMenuOpen('doctors')" x-transition class="nested-submenu">
                                    @can('view doctors')
                                        <li>
                                            <a @click.stop href="{{ route('hms.doctors.index') }}" 
                                               class="nested-link {{ request()->routeIs('hms.doctors.*') ? 'active' : '' }}">
                                                <i class="fa fa-list mr-2 w-4"></i> All Doctors
                                            </a>
                                        </li>
                                    @endcan
                                    @can('manage staff profiles')
                                        <li>
                                            <a @click.stop href="{{ route('hms.doctors.departments.index') }}" 
                                               class="nested-link {{ request()->routeIs('hms.doctors.departments.*') ? 'active' : '' }}">
                                                <i class="fa fa-building mr-2 w-4"></i> Departments
                                            </a>
                                        </li>
                                        <li>
                                            <a @click.stop href="{{ route('hms.doctor-charges.index') }}" 
                                               class="nested-link {{ request()->routeIs('hms.doctor-charges.*') ? 'active' : '' }}">
                                                <i class="fa fa-dollar-sign mr-2 w-4"></i> Doctor OPD Charges
                                            </a>
                                        </li>
                                        <li>
                                            <a @click.stop href="{{ route('hms.hr.schedules.index') }}" 
                                               class="nested-link {{ request()->routeIs('hms.hr.schedules.*') ? 'active' : '' }}">
                                                <i class="fa fa-calendar-alt mr-2 w-4"></i> Schedules / Availability
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany

                        {{-- Nurses Submenu --}}
                        @can('manage nurses')
                            <li>
                                <div class="nested-menu-item" @click="toggleMenu('nurses')">
                                    <div class="flex items-center text-sm">
                                        <i class="fa fa-user-nurse mr-2 w-4 text-blue-600"></i>
                                        <span>Nurses</span>
                                    </div>
                                    <i class="fa fa-chevron-down text-xs transition-transform" 
                                       :class="isMenuOpen('nurses') ? 'rotate-180' : ''"></i>
                                </div>
                                <ul x-show="isMenuOpen('nurses')" x-transition class="nested-submenu">
                                    <li>
                                        <a @click.stop href="{{ route('hms.nurses.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.nurses.*') ? 'active' : '' }}">
                                            <i class="fa fa-list mr-2 w-4"></i> All Nurses
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.nurses.duty-roster') }}" 
                                           class="nested-link {{ request()->routeIs('hms.nurses.duty-roster') ? 'active' : '' }}">
                                            <i class="fa fa-clipboard-list mr-2 w-4"></i> Duty Roster
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.nurses.assign-wards') }}" 
                                           class="nested-link {{ request()->routeIs('hms.nurses.assign-wards') ? 'active' : '' }}">
                                            <i class="fa fa-hospital-user mr-2 w-4"></i> Assign to Wards
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        {{-- Receptionists --}}
                        @can('manage staff profiles')
                            <li>
                                <div class="nested-menu-item" @click="toggleMenu('receptionists')">
                                    <div class="flex items-center text-sm">
                                        <i class="fa fa-user-tie mr-2 w-4 text-blue-600"></i>
                                        <span>Receptionists</span>
                                    </div>
                                    <i class="fa fa-chevron-down text-xs transition-transform" 
                                       :class="isMenuOpen('receptionists') ? 'rotate-180' : ''"></i>
                                </div>
                                <ul x-show="isMenuOpen('receptionists')" x-transition class="nested-submenu">
                                    <li>
                                        <a @click.stop href="{{ route('hms.staff.receptionists') }}" 
                                           class="nested-link {{ request()->routeIs('hms.staff.receptionists*') ? 'active' : '' }}">
                                            <i class="fa fa-user-plus mr-2 w-4"></i> Register Patients
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.appointments.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.appointments.*') ? 'active' : '' }}">
                                            <i class="fa fa-calendar-check mr-2 w-4"></i> Handle Appointments
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        {{-- Ambulance Management --}}
                        @can('manage ambulances')
                            <li>
                                <div class="nested-menu-item" @click="toggleMenu('ambulance')">
                                    <div class="flex items-center text-sm">
                                        <i class="fa fa-ambulance mr-2 w-4 text-blue-600"></i>
                                        <span>Ambulance</span>
                                    </div>
                                    <i class="fa fa-chevron-down text-xs transition-transform" 
                                       :class="isMenuOpen('ambulance') ? 'rotate-180' : ''"></i>
                                </div>
                                <ul x-show="isMenuOpen('ambulance')" x-transition class="nested-submenu">
                                    <li>
                                        <a @click.stop href="{{ route('hms.ambulance.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.ambulance.*') ? 'active' : '' }}">
                                            <i class="fa fa-car mr-2 w-4"></i> Ambulance Vehicles
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.ambulance.calls') }}" 
                                           class="nested-link {{ request()->routeIs('hms.ambulance.calls') ? 'active' : '' }}">
                                            <i class="fa fa-phone mr-2 w-4"></i> Ambulance Calls / Trips
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            {{-- 🧬 3. CLINICAL MODULES --}}
            @canany(['view prescriptions', 'manage case handlers', 'generate operation reports', 'manage bed assignments'])
                <li class="mb-1">
                    <div class="menu-item menu-item-purple" @click="toggleMenu('clinical', true)">
                        <div class="flex items-center">
                            <div class="menu-icon bg-gradient-to-br from-purple-500 to-violet-600">
                                <i class="fa fa-heartbeat text-white text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">Clinical Modules</span>
                        </div>
                        <i class="fa fa-chevron-down text-xs transition-transform text-gray-400" 
                           :class="isMenuOpen('clinical') ? 'rotate-180' : ''"></i>
                    </div>
                    <ul x-show="isMenuOpen('clinical')" x-transition class="submenu submenu-purple">
                        @can('view prescriptions')
                            <li>
                                <a @click.stop href="{{ route('hms.pharmacy.prescriptions.index') }}" 
                                   class="submenu-link {{ request()->routeIs('hms.pharmacy.prescriptions.*') && !request()->routeIs('hms.prescriptions.e-prescription.*') ? 'active' : '' }}">
                                    <i class="fa fa-prescription mr-2 w-4"></i> Prescriptions
                                </a>
                            </li>
                            <li>
                                <a @click.stop href="{{ route('hms.prescriptions.e-prescription.templates') }}" 
                                   class="submenu-link {{ request()->routeIs('hms.prescriptions.e-prescription.*') ? 'active' : '' }}">
                                    <i class="fa fa-file-prescription mr-2 w-4"></i> E-Prescription
                                    <span class="badge badge-warning ml-2">Premium</span>
                                </a>
                            </li>
                        @endcan
                        @can('manage case handlers')
                            <li>
                                <a @click.stop href="{{ route('hms.case-handlers.cases') }}" 
                                   class="submenu-link {{ request()->routeIs('hms.case-handlers.cases*') ? 'active' : '' }}">
                                    <i class="fa fa-briefcase-medical mr-2 w-4"></i> Patient Cases
                                </a>
                            </li>
                            <li>
                                <a @click.stop href="{{ route('hms.case-handlers.index') }}" 
                                   class="submenu-link {{ request()->routeIs('hms.case-handlers.index') ? 'active' : '' }}">
                                    <i class="fa fa-hands-helping mr-2 w-4"></i> Case Handlers
                                </a>
                            </li>
                        @endcan
                        <li>
                            <a @click.stop href="{{ route('hms.diagnosis.categories') }}" 
                               class="submenu-link {{ request()->routeIs('hms.diagnosis.categories*') ? 'active' : '' }}">
                                <i class="fa fa-tags mr-2 w-4"></i> Patient Diagnosis Categories
                            </a>
                        </li>
                        <li>
                            <a @click.stop href="{{ route('hms.medical-history.index') }}" 
                               class="submenu-link {{ request()->routeIs('hms.medical-history.*') ? 'active' : '' }}">
                                <i class="fa fa-file-medical-alt mr-2 w-4"></i> Medical History & Vitals
                            </a>
                        </li>
                        @can('generate operation reports')
                            <li>
                                <a @click.stop href="{{ route('hms.operations.index') }}" 
                                   class="submenu-link {{ request()->routeIs('hms.operations.*') ? 'active' : '' }}">
                                    <i class="fa fa-procedures mr-2 w-4"></i> Operation Theatre (OT)
                                </a>
                            </li>
                        @endcan
                        
                        {{-- Bed Management Submenu --}}
                        @can('manage bed assignments')
                            <li>
                                <div class="nested-menu-item" @click="toggleMenu('bed-management')">
                                    <div class="flex items-center text-sm">
                                        <i class="fa fa-bed mr-2 w-4 text-purple-600"></i>
                                        <span>Bed Management</span>
                                    </div>
                                    <i class="fa fa-chevron-down text-xs transition-transform" 
                                       :class="isMenuOpen('bed-management') ? 'rotate-180' : ''"></i>
                                </div>
                                <ul x-show="isMenuOpen('bed-management')" x-transition class="nested-submenu">
                                    <li>
                                        <a @click.stop href="{{ route('hms.bed-types.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.bed-types.*') ? 'active' : '' }}">
                                            <i class="fa fa-tags mr-2 w-4"></i> Bed Types
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.beds.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.beds.*') ? 'active' : '' }}">
                                            <i class="fa fa-bed mr-2 w-4"></i> Bed Assignments
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('iot.bed-occupancy-map') }}" 
                                           class="nested-link {{ request()->routeIs('iot.bed-occupancy-map') ? 'active' : '' }}">
                                            <i class="fa fa-map mr-2 w-4"></i> Bed Visualization
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            {{-- 🧪 4. DIAGNOSTICS & LABORATORY --}}
            @canany(['manage test categories', 'add test requests', 'enter test results', 'manage blood bank'])
                <li class="mb-1">
                    <div class="menu-item menu-item-rose" @click="toggleMenu('diagnostics', true)">
                        <div class="flex items-center">
                            <div class="menu-icon bg-gradient-to-br from-rose-500 to-pink-600">
                                <i class="fa fa-microscope text-white text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">Diagnostics & Lab</span>
                        </div>
                        <i class="fa fa-chevron-down text-xs transition-transform text-gray-400" 
                           :class="isMenuOpen('diagnostics') ? 'rotate-180' : ''"></i>
                    </div>
                    <ul x-show="isMenuOpen('diagnostics')" x-transition class="submenu submenu-rose">
                        
                        {{-- Laboratory Overview --}}
                        <li>
                            <a @click.stop href="{{ route('hms.laboratory.index') }}" 
                               class="submenu-link {{ request()->routeIs('hms.laboratory.index') ? 'active' : '' }}">
                                <i class="fa fa-flask mr-2 w-4"></i> Laboratory Overview
                            </a>
                        </li>
                        
                        {{-- Pathology Submenu --}}
                        @canany(['manage test categories', 'add test requests'])
                            <li>
                                <div class="nested-menu-item" @click="toggleMenu('pathology')">
                                    <div class="flex items-center text-sm">
                                        <i class="fa fa-vials mr-2 w-4 text-rose-600"></i>
                                        <span>Pathology</span>
                                    </div>
                                    <i class="fa fa-chevron-down text-xs transition-transform" 
                                       :class="isMenuOpen('pathology') ? 'rotate-180' : ''"></i>
                                </div>
                                <ul x-show="isMenuOpen('pathology')" x-transition class="nested-submenu">
                                    <li>
                                        <a @click.stop href="{{ route('hms.laboratory.tests.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.laboratory.tests.*') ? 'active' : '' }}">
                                            <i class="fa fa-flask mr-2 w-4"></i> Pathology Tests
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.test-categories.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.test-categories.*') ? 'active' : '' }}">
                                            <i class="fa fa-list mr-2 w-4"></i> Test Categories
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.laboratory.requests.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.laboratory.requests.*') ? 'active' : '' }}">
                                            <i class="fa fa-file-medical mr-2 w-4"></i> Test Reports
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcanany

                        {{-- Radiology Submenu --}}
                        @canany(['manage test categories', 'add test requests'])
                            <li>
                                <div class="nested-menu-item" @click="toggleMenu('radiology')">
                                    <div class="flex items-center text-sm">
                                        <i class="fa fa-x-ray mr-2 w-4 text-rose-600"></i>
                                        <span>Radiology</span>
                                    </div>
                                    <i class="fa fa-chevron-down text-xs transition-transform" 
                                       :class="isMenuOpen('radiology') ? 'rotate-180' : ''"></i>
                                </div>
                                <ul x-show="isMenuOpen('radiology')" x-transition class="nested-submenu">
                                    <li>
                                        <a @click.stop href="{{ route('hms.radiology.tests.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.radiology.tests.*') ? 'active' : '' }}">
                                            <i class="fa fa-x-ray mr-2 w-4"></i> Radiology Tests
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.test-categories.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.test-categories.*') ? 'active' : '' }}">
                                            <i class="fa fa-list mr-2 w-4"></i> Test Categories
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.radiology.requests.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.radiology.requests.*') ? 'active' : '' }}">
                                            <i class="fa fa-file-medical mr-2 w-4"></i> Radiology Reports
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcanany

                        {{-- Radiology Overview (if route exists) --}}
                        @if(Route::has('hms.radiology.index'))
                        <li>
                            <a @click.stop href="{{ route('hms.radiology.index') }}" 
                               class="submenu-link {{ request()->routeIs('hms.radiology.index') ? 'active' : '' }}">
                                <i class="fa fa-x-ray mr-2 w-4"></i> Radiology Overview
                            </a>
                        </li>
                        @endif

                        {{-- Blood Bank Submenu --}}
                        @can('manage blood bank')
                            <li>
                                <div class="nested-menu-item" @click="toggleMenu('blood-bank')">
                                    <div class="flex items-center text-sm">
                                        <i class="fa fa-tint mr-2 w-4 text-rose-600"></i>
                                        <span>Blood Bank</span>
                                    </div>
                                    <i class="fa fa-chevron-down text-xs transition-transform" 
                                       :class="isMenuOpen('blood-bank') ? 'rotate-180' : ''"></i>
                                </div>
                                <ul x-show="isMenuOpen('blood-bank')" x-transition class="nested-submenu">
                                    <li>
                                        <a @click.stop href="{{ route('hms.bloodbank.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.bloodbank.index') ? 'active' : '' }}">
                                            <i class="fa fa-flask mr-2 w-4"></i> Blood Groups
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.bloodbank.donors') }}" 
                                           class="nested-link {{ request()->routeIs('hms.bloodbank.donors*') ? 'active' : '' }}">
                                            <i class="fa fa-users mr-2 w-4"></i> Donors
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.bloodbank.requests') }}" 
                                           class="nested-link {{ request()->routeIs('hms.bloodbank.requests*') ? 'active' : '' }}">
                                            <i class="fa fa-hand-holding-medical mr-2 w-4"></i> Blood Requests
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.bloodbank.stock-levels') }}" 
                                           class="nested-link {{ request()->routeIs('hms.bloodbank.stock-levels') ? 'active' : '' }}">
                                            <i class="fa fa-chart-bar mr-2 w-4"></i> Stock Levels
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        <li>
                            <a @click.stop href="{{ route('hms.investigation-reports.index') }}" 
                               class="submenu-link {{ request()->routeIs('hms.investigation-reports.*') ? 'active' : '' }}">
                                <i class="fa fa-search mr-2 w-4"></i> Investigation Reports
                            </a>
                        </li>
                    </ul>
                </li>
            @endcanany

            {{-- 💊 5. PHARMACY & INVENTORY --}}
            @canany(['view prescriptions', 'dispense medicines', 'manage medicine inventory', 'manage packages'])
                <li class="mb-1">
                    <div class="menu-item menu-item-amber" @click="toggleMenu('pharmacy-inventory', true)">
                        <div class="flex items-center">
                            <div class="menu-icon bg-gradient-to-br from-amber-500 to-yellow-600">
                                <i class="fa fa-pills text-white text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">Pharmacy & Inventory</span>
                        </div>
                        <i class="fa fa-chevron-down text-xs transition-transform text-gray-400" 
                           :class="isMenuOpen('pharmacy-inventory') ? 'rotate-180' : ''"></i>
                    </div>
                    <ul x-show="isMenuOpen('pharmacy-inventory')" x-transition class="submenu submenu-amber">
                        
                        {{-- Medicines Submenu --}}
                        @can('manage medicine inventory')
                            <li>
                                <div class="nested-menu-item" @click="toggleMenu('medicines')">
                                    <div class="flex items-center text-sm">
                                        <i class="fa fa-pills mr-2 w-4 text-amber-600"></i>
                                        <span>Medicines</span>
                                    </div>
                                    <i class="fa fa-chevron-down text-xs transition-transform" 
                                       :class="isMenuOpen('medicines') ? 'rotate-180' : ''"></i>
                                </div>
                                <ul x-show="isMenuOpen('medicines')" x-transition class="nested-submenu">
                                    <li>
                                        <a @click.stop href="{{ route('hms.pharmacy.medicines.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.pharmacy.medicines.*') ? 'active' : '' }}">
                                            <i class="fa fa-list mr-2 w-4"></i> All Medicines
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.pharmacy.medicine-categories.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.pharmacy.medicine-categories.*') ? 'active' : '' }}">
                                            <i class="fa fa-tags mr-2 w-4"></i> Medicine Categories
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.pharmacy.medicine-brands.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.pharmacy.medicine-brands.*') ? 'active' : '' }}">
                                            <i class="fa fa-trademark mr-2 w-4"></i> Medicine Brands
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        {{-- Inventory Submenu --}}
                        <li>
                            <div class="nested-menu-item" @click="toggleMenu('inventory')">
                                <div class="flex items-center text-sm">
                                    <i class="fa fa-boxes mr-2 w-4 text-amber-600"></i>
                                    <span>Inventory</span>
                                </div>
                                <i class="fa fa-chevron-down text-xs transition-transform" 
                                   :class="isMenuOpen('inventory') ? 'rotate-180' : ''"></i>
                            </div>
                            <ul x-show="isMenuOpen('inventory')" x-transition class="nested-submenu">
                                <li>
                                    <a @click.stop href="{{ route('hms.inventory.index') }}" 
                                       class="nested-link {{ request()->routeIs('hms.inventory.index') ? 'active' : '' }}">
                                        <i class="fa fa-tachometer-alt mr-2 w-4"></i> Inventory Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.inventory.categories') }}" 
                                       class="nested-link {{ request()->routeIs('hms.inventory.categories') ? 'active' : '' }}">
                                        <i class="fa fa-tags mr-2 w-4"></i> Item Categories
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.inventory.suppliers.index') }}" 
                                       class="nested-link {{ request()->routeIs('hms.inventory.suppliers.*') ? 'active' : '' }}">
                                        <i class="fa fa-truck mr-2 w-4"></i> Suppliers
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.inventory.stock-movements.index') }}" 
                                       class="nested-link {{ request()->routeIs('hms.inventory.stock-movements.*') ? 'active' : '' }}">
                                        <i class="fa fa-exchange-alt mr-2 w-4"></i> Stock In / Out
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.inventory.purchase-orders.index') }}" 
                                       class="nested-link {{ request()->routeIs('hms.inventory.purchase-orders.*') ? 'active' : '' }}">
                                        <i class="fa fa-shopping-cart mr-2 w-4"></i> Purchase Orders
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.inventory.expiry-alerts') }}" 
                                       class="nested-link {{ request()->routeIs('hms.inventory.expiry-alerts') ? 'active' : '' }}">
                                        <i class="fa fa-exclamation-triangle mr-2 w-4"></i> Expiry Alerts
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.inventory.stock-report') }}" 
                                       class="nested-link {{ request()->routeIs('hms.inventory.stock-report') ? 'active' : '' }}">
                                        <i class="fa fa-chart-line mr-2 w-4"></i> Stock Report
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- Packages --}}
                        @can('manage packages')
                            <li>
                                <a @click.stop href="{{ route('hms.packages.index') }}" 
                                   class="submenu-link {{ request()->routeIs('hms.packages.*') ? 'active' : '' }}">
                                    <i class="fa fa-box-open mr-2 w-4"></i> Packages Management
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            {{-- 💰 6. FINANCE & ACCOUNTING --}}
            @canany(['create invoices', 'edit invoices', 'add payments', 'view payment reports'])
                <li class="mb-1">
                    <div class="menu-item menu-item-cyan" @click="toggleMenu('finance', true)">
                        <div class="flex items-center">
                            <div class="menu-icon bg-gradient-to-br from-cyan-500 to-sky-600">
                                <i class="fa fa-file-invoice-dollar text-white text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">Finance & Accounting</span>
                        </div>
                        <i class="fa fa-chevron-down text-xs transition-transform text-gray-400" 
                           :class="isMenuOpen('finance') ? 'rotate-180' : ''"></i>
                    </div>
                    <ul x-show="isMenuOpen('finance')" x-transition class="submenu submenu-cyan">
                        
                        <li>
                            <a @click.stop href="{{ route('hms.finance.index') }}" 
                               class="submenu-link {{ request()->routeIs('hms.finance.index') ? 'active' : '' }}">
                                <i class="fa fa-tachometer-alt mr-2 w-4"></i> Finance Dashboard
                            </a>
                        </li>

                        {{-- Financial Reports --}}
                        <li>
                            <div class="nested-menu-item" @click="toggleMenu('financial-reports')">
                                <div class="flex items-center text-sm">
                                    <i class="fa fa-chart-pie mr-2 w-4 text-cyan-600"></i>
                                    <span>Financial Reports</span>
                                </div>
                                <i class="fa fa-chevron-down text-xs transition-transform" 
                                   :class="isMenuOpen('financial-reports') ? 'rotate-180' : ''"></i>
                            </div>
                            <ul x-show="isMenuOpen('financial-reports')" x-transition class="nested-submenu">
                                <li>
                                    <a @click.stop href="{{ route('hms.finance.reports') }}" 
                                       class="nested-link {{ request()->routeIs('hms.finance.reports') ? 'active' : '' }}">
                                        <i class="fa fa-file-alt mr-2 w-4"></i> All Reports
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.finance.profit-loss') }}" 
                                       class="nested-link {{ request()->routeIs('hms.finance.profit-loss') ? 'active' : '' }}">
                                        <i class="fa fa-chart-line mr-2 w-4"></i> Profit & Loss
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.finance.balance-sheet') }}" 
                                       class="nested-link {{ request()->routeIs('hms.finance.balance-sheet') ? 'active' : '' }}">
                                        <i class="fa fa-balance-scale mr-2 w-4"></i> Balance Sheet
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.finance.cash-flow') }}" 
                                       class="nested-link {{ request()->routeIs('hms.finance.cash-flow') ? 'active' : '' }}">
                                        <i class="fa fa-money-bill-wave mr-2 w-4"></i> Cash Flow
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- Billing Submenu --}}
                        @can('create invoices')
                            <li>
                                <div class="nested-menu-item" @click="toggleMenu('billing')">
                                    <div class="flex items-center text-sm">
                                        <i class="fa fa-file-invoice mr-2 w-4 text-cyan-600"></i>
                                        <span>Billing</span>
                                    </div>
                                    <i class="fa fa-chevron-down text-xs transition-transform" 
                                       :class="isMenuOpen('billing') ? 'rotate-180' : ''"></i>
                                </div>
                                <ul x-show="isMenuOpen('billing')" x-transition class="nested-submenu">
                                    <li>
                                        <a @click.stop href="{{ route('hms.billing.invoices.create') }}" 
                                           class="nested-link {{ request()->routeIs('hms.billing.invoices.create') ? 'active' : '' }}">
                                            <i class="fa fa-plus mr-2 w-4"></i> Generate Bill
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.billing.invoices.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.billing.invoices.*') ? 'active' : '' }}">
                                            <i class="fa fa-list mr-2 w-4"></i> Bill List / History
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.billing.receipts') }}" 
                                           class="nested-link {{ request()->routeIs('hms.billing.receipts') ? 'active' : '' }}">
                                            <i class="fa fa-receipt mr-2 w-4"></i> Payment Receipts
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        {{-- Payments --}}
                        @can('add payments')
                            <li>
                                <div class="nested-menu-item" @click="toggleMenu('payments')">
                                    <div class="flex items-center text-sm">
                                        <i class="fa fa-credit-card mr-2 w-4 text-cyan-600"></i>
                                        <span>Payments</span>
                                    </div>
                                    <i class="fa fa-chevron-down text-xs transition-transform" 
                                       :class="isMenuOpen('payments') ? 'rotate-180' : ''"></i>
                                </div>
                                <ul x-show="isMenuOpen('payments')" x-transition class="nested-submenu">
                                    <li>
                                        <a @click.stop href="{{ route('hms.billing.payments.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.billing.payments.*') ? 'active' : '' }}">
                                            <i class="fa fa-list mr-2 w-4"></i> Payment List
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.billing.payment-reports') }}" 
                                           class="nested-link {{ request()->routeIs('hms.billing.payment-reports') ? 'active' : '' }}">
                                            <i class="fa fa-chart-line mr-2 w-4"></i> Payment Reports
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        {{-- Advance Payments --}}
                        <li>
                            <div class="nested-menu-item" @click="toggleMenu('advance-payments')">
                                <div class="flex items-center text-sm">
                                    <i class="fa fa-hand-holding-usd mr-2 w-4 text-cyan-600"></i>
                                    <span>Advance Payments</span>
                                </div>
                                <i class="fa fa-chevron-down text-xs transition-transform" 
                                   :class="isMenuOpen('advance-payments') ? 'rotate-180' : ''"></i>
                            </div>
                            <ul x-show="isMenuOpen('advance-payments')" x-transition class="nested-submenu">
                                <li>
                                    <a @click.stop href="{{ route('hms.advance-payments.deposits') }}" 
                                       class="nested-link {{ request()->routeIs('hms.advance-payments.deposits') ? 'active' : '' }}">
                                        <i class="fa fa-money-bill-wave mr-2 w-4"></i> Patient Deposits
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.advance-payments.refunds') }}" 
                                       class="nested-link {{ request()->routeIs('hms.advance-payments.refunds') ? 'active' : '' }}">
                                        <i class="fa fa-undo mr-2 w-4"></i> Refund Management
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- Accounts Management --}}
                        <li>
                            <div class="nested-menu-item" @click="toggleMenu('accounts')">
                                <div class="flex items-center text-sm">
                                    <i class="fa fa-university mr-2 w-4 text-cyan-600"></i>
                                    <span>Accounts Management</span>
                                </div>
                                <i class="fa fa-chevron-down text-xs transition-transform" 
                                   :class="isMenuOpen('accounts') ? 'rotate-180' : ''"></i>
                            </div>
                            <ul x-show="isMenuOpen('accounts')" x-transition class="nested-submenu">
                                <li>
                                    <a @click.stop href="{{ route('hms.finance.accounts.index') }}" 
                                       class="nested-link {{ request()->routeIs('hms.finance.accounts.*') ? 'active' : '' }}">
                                        <i class="fa fa-list-alt mr-2 w-4"></i> Account Heads
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.finance.ledger') }}" 
                                       class="nested-link {{ request()->routeIs('hms.finance.ledger') ? 'active' : '' }}">
                                        <i class="fa fa-book mr-2 w-4"></i> Ledger
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.finance.trial-balance') }}" 
                                       class="nested-link {{ request()->routeIs('hms.finance.trial-balance') ? 'active' : '' }}">
                                        <i class="fa fa-balance-scale mr-2 w-4"></i> Trial Balance
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.finance.chart-of-accounts') }}" 
                                       class="nested-link {{ request()->routeIs('hms.finance.chart-of-accounts') ? 'active' : '' }}">
                                        <i class="fa fa-sitemap mr-2 w-4"></i> Chart of Accounts
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- Expenses --}}
                        <li>
                            <div class="nested-menu-item" @click="toggleMenu('expenses')">
                                <div class="flex items-center text-sm">
                                    <i class="fa fa-chart-line mr-2 w-4 text-cyan-600"></i>
                                    <span>Expenses</span>
                                </div>
                                <i class="fa fa-chevron-down text-xs transition-transform" 
                                   :class="isMenuOpen('expenses') ? 'rotate-180' : ''"></i>
                            </div>
                            <ul x-show="isMenuOpen('expenses')" x-transition class="nested-submenu">
                                <li>
                                    <a @click.stop href="{{ route('hms.finance.expenses.index') }}" 
                                       class="nested-link {{ request()->routeIs('hms.finance.expenses.*') ? 'active' : '' }}">
                                        <i class="fa fa-list mr-2 w-4"></i> All Expenses
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.finance.expenses.categories') }}" 
                                       class="nested-link {{ request()->routeIs('hms.finance.expenses.categories') ? 'active' : '' }}">
                                        <i class="fa fa-tags mr-2 w-4"></i> Categories
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.finance.expenses.reports') }}" 
                                       class="nested-link {{ request()->routeIs('hms.finance.expenses.reports') ? 'active' : '' }}">
                                        <i class="fa fa-chart-bar mr-2 w-4"></i> Reports
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- Income --}}
                        <li>
                            <div class="nested-menu-item" @click="toggleMenu('income')">
                                <div class="flex items-center text-sm">
                                    <i class="fa fa-coins mr-2 w-4 text-cyan-600"></i>
                                    <span>Income</span>
                                </div>
                                <i class="fa fa-chevron-down text-xs transition-transform" 
                                   :class="isMenuOpen('income') ? 'rotate-180' : ''"></i>
                            </div>
                            <ul x-show="isMenuOpen('income')" x-transition class="nested-submenu">
                                <li>
                                    <a @click.stop href="{{ route('hms.finance.income.index') }}" 
                                       class="nested-link {{ request()->routeIs('hms.finance.income.*') ? 'active' : '' }}">
                                        <i class="fa fa-stream mr-2 w-4"></i> All Income
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.finance.income.reports') }}" 
                                       class="nested-link {{ request()->routeIs('hms.finance.income.reports') ? 'active' : '' }}">
                                        <i class="fa fa-file-alt mr-2 w-4"></i> Income Reports
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- Insurance --}}
                        <li>
                            <div class="nested-menu-item" @click="toggleMenu('insurance')">
                                <div class="flex items-center text-sm">
                                    <i class="fa fa-shield-alt mr-2 w-4 text-cyan-600"></i>
                                    <span>Insurance</span>
                                </div>
                                <i class="fa fa-chevron-down text-xs transition-transform" 
                                   :class="isMenuOpen('insurance') ? 'rotate-180' : ''"></i>
                            </div>
                            <ul x-show="isMenuOpen('insurance')" x-transition class="nested-submenu">
                                <li>
                                    <a @click.stop href="{{ route('hms.insurance.companies') }}" 
                                       class="nested-link {{ request()->routeIs('hms.insurance.companies') ? 'active' : '' }}">
                                        <i class="fa fa-building mr-2 w-4"></i> Companies
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.insurance.providers.index') }}" 
                                       class="nested-link {{ request()->routeIs('hms.insurance.providers.*') ? 'active' : '' }}">
                                        <i class="fa fa-user-shield mr-2 w-4"></i> Providers
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.insurance.claims.index') }}" 
                                       class="nested-link {{ request()->routeIs('hms.insurance.claims.*') ? 'active' : '' }}">
                                        <i class="fa fa-file-medical mr-2 w-4"></i> Claims
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.insurance.policies') }}" 
                                       class="nested-link {{ request()->routeIs('hms.insurance.policies') ? 'active' : '' }}">
                                        <i class="fa fa-clipboard-check mr-2 w-4"></i> Policy Management
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            @endcanany

            {{-- 👥 7. HUMAN RESOURCE (HR) --}}
            @canany(['manage staff profiles', 'view attendance', 'manage payrolls'])
                <li class="mb-1">
                    <div class="menu-item menu-item-orange" @click="toggleMenu('hr', true)">
                        <div class="flex items-center">
                            <div class="menu-icon bg-gradient-to-br from-orange-500 to-red-600">
                                <i class="fa fa-users-cog text-white text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">Human Resource (HR)</span>
                        </div>
                        <i class="fa fa-chevron-down text-xs transition-transform text-gray-400" 
                           :class="isMenuOpen('hr') ? 'rotate-180' : ''"></i>
                    </div>
                    <ul x-show="isMenuOpen('hr')" x-transition class="submenu submenu-orange">
                        {{-- HR Dashboard --}}
                        <li>
                            <a @click.stop href="{{ route('hms.hr.index') }}" 
                               class="submenu-link {{ request()->routeIs('hms.hr.index') ? 'active' : '' }}">
                                <i class="fa fa-tachometer-alt mr-2 w-4"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        
                        {{-- Employees Submenu --}}
                        @can('manage staff profiles')
                            <li>
                                <div class="nested-menu-item" @click="toggleMenu('employees')">
                                    <div class="flex items-center text-sm">
                                        <i class="fa fa-users mr-2 w-4 text-orange-600"></i>
                                        <span>Employees</span>
                                    </div>
                                    <i class="fa fa-chevron-down text-xs transition-transform" 
                                       :class="isMenuOpen('employees') ? 'rotate-180' : ''"></i>
                                </div>
                                <ul x-show="isMenuOpen('employees')" x-transition class="nested-submenu">
                                    <li>
                                        <a @click.stop href="{{ route('hms.hr.employees.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.hr.employees.*') ? 'active' : '' }}">
                                            <i class="fa fa-list mr-2 w-4"></i> All Employees
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.hr.employees.create') }}" 
                                           class="nested-link {{ request()->routeIs('hms.hr.employees.create') ? 'active' : '' }}">
                                            <i class="fa fa-plus mr-2 w-4"></i> Add New
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.hr.designations.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.hr.designations.*') ? 'active' : '' }}">
                                            <i class="fa fa-briefcase mr-2 w-4"></i> Designations
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.hr.departments.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.hr.departments.*') ? 'active' : '' }}">
                                            <i class="fa fa-building mr-2 w-4"></i> Departments
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.hr.appraisals.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.hr.appraisals.*') ? 'active' : '' }}">
                                            <i class="fa fa-chart-line mr-2 w-4"></i> Performance Appraisals
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        {{-- Payrolls --}}
                        @can('manage payrolls')
                            <li>
                                <div class="nested-menu-item" @click="toggleMenu('payrolls')">
                                    <div class="flex items-center text-sm">
                                        <i class="fa fa-money-bill-wave mr-2 w-4 text-orange-600"></i>
                                        <span>Payrolls</span>
                                    </div>
                                    <i class="fa fa-chevron-down text-xs transition-transform" 
                                       :class="isMenuOpen('payrolls') ? 'rotate-180' : ''"></i>
                                </div>
                                <ul x-show="isMenuOpen('payrolls')" x-transition class="nested-submenu">
                                    <li>
                                        <a @click.stop href="{{ route('hms.hr.payrolls.create') }}" 
                                           class="nested-link {{ request()->routeIs('hms.hr.payrolls.create') ? 'active' : '' }}">
                                            <i class="fa fa-calculator mr-2 w-4"></i> Generate Salary
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.hr.payrolls.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.hr.payrolls.*') ? 'active' : '' }}">
                                            <i class="fa fa-file-invoice mr-2 w-4"></i> Salary Reports
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        {{-- Attendance --}}
                        @can('view attendance')
                            <li>
                                <div class="nested-menu-item" @click="toggleMenu('attendance')">
                                    <div class="flex items-center text-sm">
                                        <i class="fa fa-clock mr-2 w-4 text-orange-600"></i>
                                        <span>Attendance</span>
                                    </div>
                                    <i class="fa fa-chevron-down text-xs transition-transform" 
                                       :class="isMenuOpen('attendance') ? 'rotate-180' : ''"></i>
                                </div>
                                <ul x-show="isMenuOpen('attendance')" x-transition class="nested-submenu">
                                    <li>
                                        <a @click.stop href="{{ route('hms.hr.attendance.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.hr.attendance.*') ? 'active' : '' }}">
                                            <i class="fa fa-calendar-check mr-2 w-4"></i> Daily Logs
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.hr.leave-requests.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.hr.leave-requests.*') ? 'active' : '' }}">
                                            <i class="fa fa-calendar-times mr-2 w-4"></i> Leave Requests
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        {{-- Leave Types --}}
                        <li>
                            <a @click.stop href="{{ route('hms.hr.leave-types.index') }}" 
                               class="submenu-link {{ request()->routeIs('hms.hr.leave-types.*') ? 'active' : '' }}">
                                <i class="fa fa-calendar-alt mr-2 w-4"></i>
                                <span>Leave Types</span>
                            </a>
                        </li>

                        {{-- Recruitment & Onboarding --}}
                        <li>
                            <div class="nested-menu-item" @click="toggleMenu('recruitment')">
                                <div class="flex items-center text-sm">
                                    <i class="fa fa-briefcase mr-2 w-4 text-orange-600"></i>
                                    <span>Recruitment</span>
                                </div>
                                <i class="fa fa-chevron-down text-xs transition-transform" 
                                   :class="isMenuOpen('recruitment') ? 'rotate-180' : ''"></i>
                            </div>
                            <ul x-show="isMenuOpen('recruitment')" x-transition class="nested-submenu">
                                <li>
                                    <a @click.stop href="{{ route('hms.hr.job-postings.index') }}" 
                                       class="nested-link {{ request()->routeIs('hms.hr.job-postings.*') ? 'active' : '' }}">
                                        <i class="fa fa-clipboard-list mr-2 w-4"></i> Job Postings
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.hr.job-applications.index') }}" 
                                       class="nested-link {{ request()->routeIs('hms.hr.job-applications.*') ? 'active' : '' }}">
                                        <i class="fa fa-file-alt mr-2 w-4"></i> Applications
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- Training & Development --}}
                        <li>
                            <a @click.stop href="{{ route('hms.hr.training-programs.index') }}" 
                               class="submenu-link {{ request()->routeIs('hms.hr.training-programs.*') ? 'active' : '' }}">
                                <i class="fa fa-graduation-cap mr-2 w-4"></i>
                                <span>Training Programs</span>
                            </a>
                        </li>

                        {{-- Announcements --}}
                        <li>
                            <a @click.stop href="{{ route('hms.hr.announcements.index') }}" 
                               class="submenu-link {{ request()->routeIs('hms.hr.announcements.*') ? 'active' : '' }}">
                                <i class="fa fa-bullhorn mr-2 w-4"></i>
                                <span>Announcements</span>
                            </a>
                        </li>

                        {{-- Shift Management --}}
                        <li>
                            <div class="nested-menu-item" @click="toggleMenu('shifts')">
                                <div class="flex items-center text-sm">
                                    <i class="fa fa-calendar-check mr-2 w-4 text-orange-600"></i>
                                    <span>Shifts</span>
                                </div>
                                <i class="fa fa-chevron-down text-xs transition-transform" 
                                   :class="isMenuOpen('shifts') ? 'rotate-180' : ''"></i>
                            </div>
                            <ul x-show="isMenuOpen('shifts')" x-transition class="nested-submenu">
                                <li>
                                    <a @click.stop href="{{ route('hms.hr.shifts.index') }}" 
                                       class="nested-link {{ request()->routeIs('hms.hr.shifts.*') ? 'active' : '' }}">
                                        <i class="fa fa-clock mr-2 w-4"></i> Shift Types
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.hr.schedules.index') }}" 
                                       class="nested-link {{ request()->routeIs('hms.hr.schedules.*') ? 'active' : '' }}">
                                        <i class="fa fa-calendar-week mr-2 w-4"></i> Roster Builder
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- Public Holidays --}}
                        <li>
                            <a @click.stop href="{{ route('hms.hr.public-holidays.index') }}" 
                               class="submenu-link {{ request()->routeIs('hms.hr.public-holidays.*') ? 'active' : '' }}">
                                <i class="fa fa-calendar-day mr-2 w-4"></i>
                                <span>Public Holidays</span>
                            </a>
                        </li>

                        {{-- HR Reports --}}
                        <li>
                            <div class="nested-menu-item" @click="toggleMenu('hr-reports')">
                                <div class="flex items-center text-sm">
                                    <i class="fa fa-chart-bar mr-2 w-4 text-orange-600"></i>
                                    <span>Reports</span>
                                </div>
                                <i class="fa fa-chevron-down text-xs transition-transform" 
                                   :class="isMenuOpen('hr-reports') ? 'rotate-180' : ''"></i>
                            </div>
                            <ul x-show="isMenuOpen('hr-reports')" x-transition class="nested-submenu">
                                <li>
                                    <a @click.stop href="{{ route('hms.hr.reports.employee-list') }}" 
                                       class="nested-link {{ request()->routeIs('hms.hr.reports.employee-list') ? 'active' : '' }}">
                                        <i class="fa fa-users mr-2 w-4"></i> Employee List
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.hr.reports.leave') }}" 
                                       class="nested-link {{ request()->routeIs('hms.hr.reports.leave') ? 'active' : '' }}">
                                        <i class="fa fa-calendar-times mr-2 w-4"></i> Leave Report
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.hr.reports.attendance') }}" 
                                       class="nested-link {{ request()->routeIs('hms.hr.reports.attendance') ? 'active' : '' }}">
                                        <i class="fa fa-clock mr-2 w-4"></i> Attendance Report
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.hr.reports.payroll-summary') }}" 
                                       class="nested-link {{ request()->routeIs('hms.hr.reports.payroll-summary') ? 'active' : '' }}">
                                        <i class="fa fa-money-bill-wave mr-2 w-4"></i> Payroll Summary
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.hr.reports.headcount-trends') }}" 
                                       class="nested-link {{ request()->routeIs('hms.hr.reports.headcount-trends') ? 'active' : '' }}">
                                        <i class="fa fa-chart-line mr-2 w-4"></i> Headcount Trends
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.hr.reports.attrition') }}" 
                                       class="nested-link {{ request()->routeIs('hms.hr.reports.attrition') ? 'active' : '' }}">
                                        <i class="fa fa-user-minus mr-2 w-4"></i> Attrition Report
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.hr.reports.salary-expense') }}" 
                                       class="nested-link {{ request()->routeIs('hms.hr.reports.salary-expense') ? 'active' : '' }}">
                                        <i class="fa fa-dollar-sign mr-2 w-4"></i> Salary Expense
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.hr.reports.training-participation') }}" 
                                       class="nested-link {{ request()->routeIs('hms.hr.reports.training-participation') ? 'active' : '' }}">
                                        <i class="fa fa-graduation-cap mr-2 w-4"></i> Training Participation
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- HR Settings --}}
                        <li>
                            <a @click.stop href="{{ route('hms.hr.settings.index') }}" 
                               class="submenu-link {{ request()->routeIs('hms.hr.settings.*') ? 'active' : '' }}">
                                <i class="fa fa-cog mr-2 w-4"></i>
                                <span>HR Settings</span>
                            </a>
                        </li>

                        {{-- Documents --}}
                        <li>
                            <div class="nested-menu-item" @click="toggleMenu('hr-documents')">
                                <div class="flex items-center text-sm">
                                    <i class="fa fa-file-alt mr-2 w-4 text-orange-600"></i>
                                    <span>Documents</span>
                                </div>
                                <i class="fa fa-chevron-down text-xs transition-transform" 
                                   :class="isMenuOpen('hr-documents') ? 'rotate-180' : ''"></i>
                            </div>
                            <ul x-show="isMenuOpen('hr-documents')" x-transition class="nested-submenu">
                                <li>
                                    <a @click.stop href="{{ route('hms.hr.document-types') }}" 
                                       class="nested-link {{ request()->routeIs('hms.hr.document-types') ? 'active' : '' }}">
                                        <i class="fa fa-tags mr-2 w-4"></i> Document Types
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.hr.documents.index') }}" 
                                       class="nested-link {{ request()->routeIs('hms.hr.documents.*') ? 'active' : '' }}">
                                        <i class="fa fa-folder-open mr-2 w-4"></i> Staff Documents
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            @endcanany

            {{-- 📊 8. REPORTS & ANALYTICS --}}
            @canany(['generate patient reports', 'generate billing reports', 'view dashboard analytics'])
                <li class="mb-1">
                    <div class="menu-item menu-item-green" @click="toggleMenu('reports', true)">
                        <div class="flex items-center">
                            <div class="menu-icon bg-gradient-to-br from-green-500 to-emerald-600">
                                <i class="fa fa-chart-bar text-white text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">Reports & Analytics</span>
                        </div>
                        <i class="fa fa-chevron-down text-xs transition-transform text-gray-400" 
                           :class="isMenuOpen('reports') ? 'rotate-180' : ''"></i>
                    </div>
                    <ul x-show="isMenuOpen('reports')" x-transition class="submenu submenu-green">
                        @can('generate billing reports')
                            <li>
                                <a @click.stop href="{{ route('hms.reports.revenue') }}" 
                                   class="submenu-link {{ request()->routeIs('hms.reports.revenue') ? 'active' : '' }}">
                                    <i class="fa fa-chart-line mr-2 w-4"></i> Revenue Report
                                </a>
                            </li>
                            <li>
                                <a @click.stop href="{{ route('hms.reports.billing') }}" 
                                   class="submenu-link {{ request()->routeIs('hms.reports.billing') ? 'active' : '' }}">
                                    <i class="fa fa-file-invoice mr-2 w-4"></i> Billing Report
                                </a>
                            </li>
                        @endcan
                        <li>
                            <a @click.stop href="{{ route('hms.reports.lab') }}" 
                               class="submenu-link {{ request()->routeIs('hms.reports.lab') ? 'active' : '' }}">
                                <i class="fa fa-vial mr-2 w-4"></i> Lab Report
                            </a>
                        </li>
                        <li>
                            <a @click.stop href="{{ route('hms.reports.pharmacy') }}" 
                               class="submenu-link {{ request()->routeIs('hms.reports.pharmacy') ? 'active' : '' }}">
                                <i class="fa fa-pills mr-2 w-4"></i> Pharmacy Report
                            </a>
                        </li>
                        <li>
                            <a @click.stop href="{{ route('hms.reports.blood-bank') }}" 
                               class="submenu-link {{ request()->routeIs('hms.reports.blood-bank') ? 'active' : '' }}">
                                <i class="fa fa-tint mr-2 w-4"></i> Blood Bank Report
                            </a>
                        </li>
                        <li>
                            <a @click.stop href="{{ route('hms.reports.bed-occupancy') }}" 
                               class="submenu-link {{ request()->routeIs('hms.reports.bed-occupancy') ? 'active' : '' }}">
                                <i class="fa fa-bed mr-2 w-4"></i> Bed Occupancy Report
                            </a>
                        </li>
                        <li>
                            <a @click.stop href="{{ route('hms.reports.diagnosis') }}" 
                               class="submenu-link {{ request()->routeIs('hms.reports.diagnosis') ? 'active' : '' }}">
                                <i class="fa fa-diagnoses mr-2 w-4"></i> Diagnosis Report
                            </a>
                        </li>
                        <li>
                            <a @click.stop href="{{ route('hms.reports.doctor-performance') }}" 
                               class="submenu-link {{ request()->routeIs('hms.reports.doctor-performance') ? 'active' : '' }}">
                                <i class="fa fa-user-md mr-2 w-4"></i> Doctor Performance Report
                            </a>
                        </li>
                        <li class="menu-divider"></li>
                        <li>
                            <a @click.stop href="{{ route('hms.reports.custom-builder.index') }}" 
                               class="submenu-link {{ request()->routeIs('hms.reports.custom-builder.*') ? 'active' : '' }}">
                                <i class="fa fa-tools mr-2 w-4"></i> Custom Report Builder
                                <span class="badge badge-warning ml-2">Premium</span>
                            </a>
                        </li>
                        <li>
                            <a @click.stop href="{{ route('hms.reports.expense') }}" 
                               class="submenu-link {{ request()->routeIs('hms.reports.expense') ? 'active' : '' }}">
                                <i class="fa fa-money-bill-alt mr-2 w-4"></i> Expense Report
                            </a>
                        </li>
                        @can('generate birth reports')
                            <li>
                                <a @click.stop href="{{ route('hms.reports.birth') }}" 
                                   class="submenu-link {{ request()->routeIs('hms.reports.birth') ? 'active' : '' }}">
                                    <i class="fa fa-baby mr-2 w-4"></i> Birth Reports
                                </a>
                            </li>
                        @endcan
                        @can('generate death reports')
                            <li>
                                <a @click.stop href="{{ route('hms.reports.death') }}" 
                                   class="submenu-link {{ request()->routeIs('hms.reports.death') ? 'active' : '' }}">
                                    <i class="fa fa-cross mr-2 w-4"></i> Death Reports
                                </a>
                            </li>
                        @endcan
                        <li>
                            <a @click.stop href="{{ route('hms.reports.summary') }}" 
                               class="submenu-link {{ request()->routeIs('hms.reports.summary') ? 'active' : '' }}">
                                <i class="fa fa-file-alt mr-2 w-4"></i> Summary Reports
                            </a>
                        </li>
                        <li>
                            <a @click.stop href="{{ route('hms.reports.export-patients') }}" 
                               class="submenu-link {{ request()->routeIs('hms.reports.export-patients') ? 'active' : '' }}">
                                <i class="fa fa-download mr-2 w-4"></i> Export All Data
                            </a>
                        </li>
                    </ul>
                </li>
            @endcanany

            {{-- 📨 9. COMMUNICATION & FRONTDESK --}}
            @canany(['create appointments', 'manage appointments', 'view appointments'])
                <li class="mb-1">
                    <div class="menu-item menu-item-red" @click="toggleMenu('communication', true)">
                        <div class="flex items-center">
                            <div class="menu-icon bg-gradient-to-br from-red-500 to-rose-600">
                                <i class="fa fa-comments text-white text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">Communication & Frontdesk</span>
                        </div>
                        <i class="fa fa-chevron-down text-xs transition-transform text-gray-400" 
                           :class="isMenuOpen('communication') ? 'rotate-180' : ''"></i>
                    </div>
                    <ul x-show="isMenuOpen('communication')" x-transition class="submenu submenu-red">
                        
                        {{-- Appointments Submenu --}}
                        @canany(['create appointments', 'view appointments'])
                            <li>
                                <div class="nested-menu-item" @click="toggleMenu('appointments')">
                                    <div class="flex items-center text-sm">
                                        <i class="fa fa-calendar-check mr-2 w-4 text-red-600"></i>
                                        <span>Appointments</span>
                                    </div>
                                    <i class="fa fa-chevron-down text-xs transition-transform" 
                                       :class="isMenuOpen('appointments') ? 'rotate-180' : ''"></i>
                                </div>
                                <ul x-show="isMenuOpen('appointments')" x-transition class="nested-submenu">
                                    <li>
                                        <a @click.stop href="{{ route('hms.appointments.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.appointments.*') ? 'active' : '' }}">
                                            <i class="fa fa-list mr-2 w-4"></i> Manage Appointments
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.calendar.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.calendar.*') ? 'active' : '' }}">
                                            <i class="fa fa-calendar-alt mr-2 w-4"></i> Calendar View
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('admin.appointments.requests') }}" 
                                           class="nested-link {{ request()->routeIs('admin.appointments.requests') ? 'active' : '' }}">
                                            <i class="fa fa-globe mr-2 w-4"></i> Online Requests
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcanany

                        {{-- Queue Management --}}
                        @canany(['manage queue', 'view appointments', 'create appointments'])
                            <li>
                                <div class="nested-menu-item" @click="toggleMenu('queue')">
                                    <div class="flex items-center text-sm">
                                        <i class="fa fa-ticket-alt mr-2 w-4 text-purple-600"></i>
                                        <span>Queue Management</span>
                                    </div>
                                    <i class="fa fa-chevron-down text-xs transition-transform" 
                                       :class="isMenuOpen('queue') ? 'rotate-180' : ''"></i>
                                </div>
                                <ul x-show="isMenuOpen('queue')" x-transition class="nested-submenu">
                                    <li>
                                        <a @click.stop href="{{ route('hms.queue.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.queue.index') || request()->routeIs('hms.queue.create') ? 'active' : '' }}">
                                            <i class="fa fa-list mr-2 w-4"></i> Manage Queues
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.queue.token-generation') }}" 
                                           class="nested-link {{ request()->routeIs('hms.queue.token-generation') || request()->routeIs('hms.queue.generate-token') ? 'active' : '' }}">
                                            <i class="fa fa-qrcode mr-2 w-4"></i> Generate Token
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.queue.display-board') }}" target="_blank"
                                           class="nested-link {{ request()->routeIs('hms.queue.display-board') ? 'active' : '' }}">
                                            <i class="fa fa-tv mr-2 w-4"></i> Display Board
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.queue.kiosk') }}" target="_blank"
                                           class="nested-link {{ request()->routeIs('hms.queue.kiosk') ? 'active' : '' }}">
                                            <i class="fa fa-desktop mr-2 w-4"></i> Kiosk Mode
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.queue.smart-display') }}" target="_blank"
                                           class="nested-link {{ request()->routeIs('hms.queue.smart-display') ? 'active' : '' }}">
                                            <i class="fa fa-tv mr-2 w-4"></i> Smart Display
                                            <span class="badge badge-warning ml-2">Premium</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcanany

                        {{-- Enquiries --}}
                        <li>
                            <div class="nested-menu-item" @click="toggleMenu('enquiries')">
                                <div class="flex items-center text-sm">
                                    <i class="fa fa-question-circle mr-2 w-4 text-red-600"></i>
                                    <span>Enquiries</span>
                                </div>
                                <i class="fa fa-chevron-down text-xs transition-transform" 
                                   :class="isMenuOpen('enquiries') ? 'rotate-180' : ''"></i>
                            </div>
                            <ul x-show="isMenuOpen('enquiries')" x-transition class="nested-submenu">
                                <li>
                                    <a @click.stop href="{{ route('admin.enquiries.index') }}" 
                                       class="nested-link {{ request()->routeIs('admin.enquiries.index') ? 'active' : '' }}">
                                        <i class="fa fa-inbox mr-2 w-4"></i> Front Desk Enquiries
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.enquiries.feedback') }}" 
                                       class="nested-link {{ request()->routeIs('hms.enquiries.feedback') ? 'active' : '' }}">
                                        <i class="fa fa-comment-dots mr-2 w-4"></i> Feedback / Complaints
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- Notice Board --}}
                        <li>
                            <div class="nested-menu-item" @click="toggleMenu('notices')">
                                <div class="flex items-center text-sm">
                                    <i class="fa fa-bullhorn mr-2 w-4 text-red-600"></i>
                                    <span>Notice Board</span>
                                </div>
                                <i class="fa fa-chevron-down text-xs transition-transform" 
                                   :class="isMenuOpen('notices') ? 'rotate-180' : ''"></i>
                            </div>
                            <ul x-show="isMenuOpen('notices')" x-transition class="nested-submenu">
                                <li>
                                    <a @click.stop href="{{ route('admin.notices.index') }}" 
                                       class="nested-link {{ request()->routeIs('admin.notices.index') ? 'active' : '' }}">
                                        <i class="fa fa-clipboard mr-2 w-4"></i> Announcements
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.notices.staff') }}" 
                                       class="nested-link {{ request()->routeIs('hms.notices.staff') ? 'active' : '' }}">
                                        <i class="fa fa-user-tie mr-2 w-4"></i> Staff Notices
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- Send Mails / SMS --}}
                        <li>
                            <div class="nested-menu-item" @click="toggleMenu('messaging')">
                                <div class="flex items-center text-sm">
                                    <i class="fa fa-envelope mr-2 w-4 text-red-600"></i>
                                    <span>Send Mails / SMS</span>
                                </div>
                                <i class="fa fa-chevron-down text-xs transition-transform" 
                                   :class="isMenuOpen('messaging') ? 'rotate-180' : ''"></i>
                            </div>
                            <ul x-show="isMenuOpen('messaging')" x-transition class="nested-submenu">
                                <li>
                                    <a @click.stop href="{{ route('hms.messaging.index') }}" 
                                       class="nested-link {{ request()->routeIs('hms.messaging.index') ? 'active' : '' }}">
                                        <i class="fa fa-tachometer-alt mr-2 w-4"></i> Messages Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.messaging.bulk') }}" 
                                       class="nested-link {{ request()->routeIs('hms.messaging.bulk') ? 'active' : '' }}">
                                        <i class="fa fa-paper-plane mr-2 w-4"></i> Bulk Messages
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.messaging.templates') }}" 
                                       class="nested-link {{ request()->routeIs('hms.messaging.templates') ? 'active' : '' }}">
                                        <i class="fa fa-file-alt mr-2 w-4"></i> Templates
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- Reminders --}}
                        <li>
                            <div class="nested-menu-item" @click="toggleMenu('reminders')">
                                <div class="flex items-center text-sm">
                                    <i class="fa fa-bell mr-2 w-4 text-red-600"></i>
                                    <span>Reminders</span>
                                </div>
                                <i class="fa fa-chevron-down text-xs transition-transform" 
                                   :class="isMenuOpen('reminders') ? 'rotate-180' : ''"></i>
                            </div>
                            <ul x-show="isMenuOpen('reminders')" x-transition class="nested-submenu">
                                <li>
                                    <a @click.stop href="{{ route('hms.reminders.index') }}" 
                                       class="nested-link {{ request()->routeIs('hms.reminders.index') ? 'active' : '' }}">
                                        <i class="fa fa-list mr-2 w-4"></i> All Reminders
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.reminders.appointments') }}" 
                                       class="nested-link {{ request()->routeIs('hms.reminders.appointments') ? 'active' : '' }}">
                                        <i class="fa fa-calendar-plus mr-2 w-4"></i> Appointment Reminder
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.reminders.payments') }}" 
                                       class="nested-link {{ request()->routeIs('hms.reminders.payments') ? 'active' : '' }}">
                                        <i class="fa fa-dollar-sign mr-2 w-4"></i> Payment Reminder
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            @endcanany

            {{-- ⚙️ 10. SYSTEM ADMINISTRATION --}}
            @canany(['manage system settings', 'manage roles', 'manage permissions', 'view audit logs'])
                <li class="mb-1">
                    <div class="menu-item menu-item-indigo" @click="toggleMenu('settings', true)">
                        <div class="flex items-center">
                            <div class="menu-icon bg-gradient-to-br from-indigo-500 to-purple-600">
                                <i class="fa fa-cog text-white text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">System Administration</span>
                        </div>
                        <i class="fa fa-chevron-down text-xs transition-transform text-gray-400" 
                           :class="isMenuOpen('settings') ? 'rotate-180' : ''"></i>
                    </div>
                    <ul x-show="isMenuOpen('settings')" x-transition class="submenu submenu-indigo">
                        @can('manage system settings')
                            <li>
                                <div class="nested-menu-item" @click="toggleMenu('general-settings')">
                                    <div class="flex items-center text-sm">
                                        <i class="fa fa-sliders-h mr-2 w-4 text-indigo-600"></i>
                                        <span>General Settings</span>
                                    </div>
                                    <i class="fa fa-chevron-down text-xs transition-transform" 
                                       :class="isMenuOpen('general-settings') ? 'rotate-180' : ''"></i>
                                </div>
                                <ul x-show="isMenuOpen('general-settings')" x-transition class="nested-submenu">
                                    <li>
                                        <a @click.stop href="{{ route('hms.settings.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.settings.index') ? 'active' : '' }}">
                                            <i class="fa fa-hospital mr-2 w-4"></i> Hospital Info
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.settings.branches') }}" 
                                           class="nested-link {{ request()->routeIs('hms.settings.branches.*') ? 'active' : '' }}">
                                            <i class="fa fa-building mr-2 w-4"></i> Branch Setup
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.system.timezone') }}" 
                                           class="nested-link {{ request()->routeIs('hms.system.timezone') ? 'active' : '' }}">
                                            <i class="fa fa-globe mr-2 w-4"></i> Timezone, Currency
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.system.theme') }}" 
                                           class="nested-link {{ request()->routeIs('hms.system.theme') ? 'active' : '' }}">
                                            <i class="fa fa-palette mr-2 w-4"></i> Logo, Theme, Dark Mode
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('hms.settings.theme') }}" 
                                           class="nested-link {{ request()->routeIs('hms.settings.theme.*') ? 'active' : '' }}">
                                            <i class="fa fa-paint-brush mr-2 w-4"></i> Theme Customizer
                                            <span class="badge badge-warning ml-2">Premium</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        {{-- User Management --}}
                        @can('manage roles')
                            <li>
                                <div class="nested-menu-item" @click="toggleMenu('user-management')">
                                    <div class="flex items-center text-sm">
                                        <i class="fa fa-users-cog mr-2 w-4 text-indigo-600"></i>
                                        <span>User Management</span>
                                    </div>
                                    <i class="fa fa-chevron-down text-xs transition-transform" 
                                       :class="isMenuOpen('user-management') ? 'rotate-180' : ''"></i>
                                </div>
                                <ul x-show="isMenuOpen('user-management')" x-transition class="nested-submenu">
                                    <li>
                                        <a @click.stop href="{{ route('hms.system.users.index') }}" 
                                           class="nested-link {{ request()->routeIs('hms.system.users.*') ? 'active' : '' }}">
                                            <i class="fa fa-users mr-2 w-4"></i> Users
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.stop href="{{ route('admin.roles.index') }}" 
                                           class="nested-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                                            <i class="fa fa-user-shield mr-2 w-4"></i> Roles & Permissions
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        <li>
                            <a @click.stop href="{{ route('admin.modules.index') }}" 
                               class="submenu-link {{ request()->routeIs('admin.modules.*') ? 'active' : '' }}">
                                <i class="fa fa-puzzle-piece mr-2 w-4"></i> Module Manager
                            </a>
                        </li>
                        <li>
                            <a @click.stop href="{{ route('hms.system.localization') }}" 
                               class="submenu-link {{ request()->routeIs('hms.system.localization') ? 'active' : '' }}">
                                <i class="fa fa-language mr-2 w-4"></i> Localization
                            </a>
                        </li>
                        @can('manage backups')
                            <li>
                                <a @click.stop href="{{ route('hms.settings.backup') }}" 
                                   class="submenu-link {{ request()->routeIs('hms.settings.backup.*') ? 'active' : '' }}">
                                    <i class="fa fa-database mr-2 w-4"></i> Backup & Restore
                                </a>
                            </li>
                        @endcan
                        @can('view audit logs')
                            <li>
                                <a @click.stop href="{{ route('hms.settings.audit-logs') }}" 
                                   class="submenu-link {{ request()->routeIs('hms.settings.audit-logs') ? 'active' : '' }}">
                                    <i class="fa fa-history mr-2 w-4"></i> Activity Logs / Audit Trail
                                </a>
                            </li>
                        @endcan
                        <li>
                            <a @click.stop href="{{ route('hms.system.api-keys') }}" 
                               class="submenu-link {{ request()->routeIs('hms.system.api-keys') ? 'active' : '' }}">
                                <i class="fa fa-key mr-2 w-4"></i> API Keys & Integrations
                            </a>
                        </li>
                    </ul>
                </li>
            @endcanany

            {{-- 🌐 11. FRONTEND CMS --}}
            @canany(['manage homepage', 'manage services', 'manage doctors listing', 'manage marketing'])
                <li class="mb-1">
                    <div class="menu-item menu-item-purple" @click="toggleMenu('cms', true)">
                        <div class="flex items-center">
                            <div class="menu-icon bg-gradient-to-br from-purple-500 to-fuchsia-600">
                                <i class="fa fa-browser text-white text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">Frontend CMS</span>
                        </div>
                        <i class="fa fa-chevron-down text-xs transition-transform text-gray-400" 
                           :class="isMenuOpen('cms') ? 'rotate-180' : ''">                        </i>
                    </div>
                    <ul x-show="isMenuOpen('cms')" x-transition class="submenu submenu-purple">
                        <li>
                            <a @click.stop href="{{ route('cms.home') }}" 
                               class="submenu-link {{ request()->routeIs('cms.home') ? 'active' : '' }}">
                                <i class="fa fa-home mr-2 w-4"></i> Home Page
                            </a>
                        </li>
                        <li>
                        <a @click.stop href="{{ route('cms.services') }}" 
                           class="submenu-link {{ request()->routeIs('cms.services') ? 'active' : '' }}">
                            <i class="fa fa-briefcase mr-2 w-4"></i> Services Page
                        </a>
                    </li>
                    <li>
                        <a @click.stop href="{{ route('cms.doctors-page') }}" 
                           class="submenu-link {{ request()->routeIs('cms.doctors-page') ? 'active' : '' }}">
                            <i class="fa fa-user-md mr-2 w-4"></i> Doctors Page
                        </a>
                    </li>
                    <li>
                        <a @click.stop href="{{ route('cms.about') }}" 
                           class="submenu-link {{ request()->routeIs('cms.about') ? 'active' : '' }}">
                            <i class="fa fa-info-circle mr-2 w-4"></i> About Page
                        </a>
                    </li>
                    <li>
                        <a @click.stop href="{{ route('cms.contact-page') }}" 
                           class="submenu-link {{ request()->routeIs('cms.contact-page') ? 'active' : '' }}">
                            <i class="fa fa-phone mr-2 w-4"></i> Contact Page
                        </a>
                    </li>
                    <li>
                        <a @click.stop href="{{ route('cms.features') }}" 
                           class="submenu-link {{ request()->routeIs('cms.features') ? 'active' : '' }}">
                            <i class="fa fa-star mr-2 w-4"></i> Features Page
                        </a>
                    </li>
                    <li>
                        <a @click.stop href="{{ route('cms.header-footer') }}" 
                           class="submenu-link {{ request()->routeIs('cms.header-footer') ? 'active' : '' }}">
                            <i class="fa fa-header mr-2 w-4"></i> Header & Footer
                        </a>
                    </li>
                    <li>
                        <a @click.stop href="{{ route('cms.blog.index') }}" 
                           class="submenu-link {{ request()->routeIs('cms.blog.*') ? 'active' : '' }}">
                            <i class="fa fa-newspaper mr-2 w-4"></i> News / Blog
                        </a>
                    </li>
                    <li>
                        <a @click.stop href="{{ route('cms.gallery.index') }}" 
                           class="submenu-link {{ request()->routeIs('cms.gallery.*') ? 'active' : '' }}">
                            <i class="fa fa-images mr-2 w-4"></i> Gallery
                        </a>
                    </li>
                    <li>
                        <a @click.stop href="{{ route('cms.testimonials.index') }}" 
                           class="submenu-link {{ request()->routeIs('cms.testimonials.*') ? 'active' : '' }}">
                            <i class="fa fa-star mr-2 w-4"></i> Testimonials
                        </a>
                    </li>
                    <li>
                        <a @click.stop href="{{ route('cms.careers.index') }}" 
                           class="submenu-link {{ request()->routeIs('cms.careers.*') ? 'active' : '' }}">
                            <i class="fa fa-briefcase mr-2 w-4"></i> Careers / Jobs
                        </a>
                    </li>
                    <li>
                        <a @click.stop href="{{ route('cms.contact-inquiries') }}" 
                           class="submenu-link {{ request()->routeIs('cms.contact-inquiries') ? 'active' : '' }}">
                            <i class="fa fa-envelope-open-text mr-2 w-4"></i> Contact Form Inquiries
                        </a>
                    </li>
                    <li>
                        <a @click.stop href="{{ route('cms.seo') }}" 
                           class="submenu-link {{ request()->routeIs('cms.seo') ? 'active' : '' }}">
                            <i class="fa fa-search mr-2 w-4"></i> SEO Settings
                        </a>
                    </li>
                </ul>
            </li>
            @endcanany

            {{-- 📢 12. MARKETING SUITE --}}
            @canany(['manage marketing', 'create marketing posts', 'manage campaigns', 'manage social accounts'])
                <li class="mb-1">
                    <div class="menu-item menu-item-blue" @click="toggleMenu('marketing', true)">
                        <div class="flex items-center">
                            <div class="menu-icon bg-gradient-to-br from-blue-500 to-cyan-600">
                                <i class="fa fa-bullhorn text-white text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">Marketing Suite</span>
                        </div>
                        <i class="fa fa-chevron-down text-xs transition-transform text-gray-400" 
                           :class="isMenuOpen('marketing') ? 'rotate-180' : ''">                        </i>
                    </div>
                    <ul x-show="isMenuOpen('marketing')" x-transition class="submenu submenu-blue">
                        <li>
                        <a @click.stop href="{{ route('marketing.dashboard') }}" 
                           class="submenu-link {{ request()->routeIs('marketing.dashboard') ? 'active' : '' }}">
                            <i class="fa fa-chart-line mr-2 w-4"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a @click.stop href="{{ route('marketing.posts.index') }}" 
                           class="submenu-link {{ request()->routeIs('marketing.posts.*') ? 'active' : '' }}">
                            <i class="fa fa-edit mr-2 w-4"></i> AI Content Writer
                        </a>
                    </li>
                    <li>
                        <a @click.stop href="{{ route('marketing.campaigns.index') }}" 
                           class="submenu-link {{ request()->routeIs('marketing.campaigns.*') ? 'active' : '' }}">
                            <i class="fa fa-calendar-check mr-2 w-4"></i> Campaigns
                        </a>
                    </li>
                    <li>
                        <a @click.stop href="{{ route('marketing.scheduler.index') }}" 
                           class="submenu-link {{ request()->routeIs('marketing.scheduler.*') ? 'active' : '' }}">
                            <i class="fa fa-clock mr-2 w-4"></i> Scheduler
                        </a>
                    </li>
                    <li>
                        <a @click.stop href="{{ route('marketing.social-accounts.index') }}" 
                           class="submenu-link {{ request()->routeIs('marketing.social-accounts.*') ? 'active' : '' }}">
                            <i class="fa fa-share-alt mr-2 w-4"></i> Social Accounts
                        </a>
                    </li>
                    <li>
                        <a @click.stop href="{{ route('marketing.comments.index') }}" 
                           class="submenu-link {{ request()->routeIs('marketing.comments.*') ? 'active' : '' }}">
                            <i class="fa fa-comments mr-2 w-4"></i> Comment Replies
                        </a>
                    </li>
                    <li>
                        <a @click.stop href="{{ route('marketing.graphics.index') }}" 
                           class="submenu-link {{ request()->routeIs('marketing.graphics.*') ? 'active' : '' }}">
                            <i class="fa fa-image mr-2 w-4"></i> Graphics & Assets
                        </a>
                    </li>
                    <li>
                        <a @click.stop href="{{ route('marketing.seo.index') }}" 
                           class="submenu-link {{ request()->routeIs('marketing.seo.*') ? 'active' : '' }}">
                            <i class="fa fa-search-plus mr-2 w-4"></i> SEO Manager
                        </a>
                    </li>
                </ul>
            </li>
            @endcanany

            {{-- 🤖 13. AI, INTEGRATIONS & TOOLS --}}
            @canany(['use ai assistant', 'manage ai suggestions', 'view analytics', 'use telemedicine', 'manage rfid tags', 'monitor iot sensors'])
                <li class="mb-1">
                    <div class="menu-item menu-item-cyan" @click="toggleMenu('ai-integrations', true)">
                        <div class="flex items-center">
                            <div class="menu-icon bg-gradient-to-br from-cyan-500 to-blue-600">
                                <i class="fa fa-robot text-white text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">AI, Integrations & Tools</span>
                        </div>
                        <i class="fa fa-chevron-down text-xs transition-transform text-gray-400" 
                           :class="isMenuOpen('ai-integrations') ? 'rotate-180' : ''"></i>
                    </div>
                    <ul x-show="isMenuOpen('ai-integrations')" x-transition class="submenu submenu-cyan">
                        @can('use ai assistant')
                            <li>
                                <a @click.stop href="{{ route('ai.elliana-d') }}" 
                                   class="submenu-link {{ request()->routeIs('ai.elliana-d*') ? 'active' : '' }}">
                                    <i class="fa fa-user-nurse mr-2 w-4 text-pink-500"></i> <span class="font-semibold">Elliana D</span> - Virtual Nurse
                                </a>
                            </li>
                            <li>
                                <a @click.stop href="{{ route('ai.appointment-suggestions') }}" 
                                   class="submenu-link {{ request()->routeIs('ai.appointment-suggestions') ? 'active' : '' }}">
                                    <i class="fa fa-brain mr-2 w-4"></i> AI Dashboard Insights
                                </a>
                            </li>
                            <li>
                                <a @click.stop href="{{ route('hms.ai.predictive-analytics') }}" 
                                   class="submenu-link {{ request()->routeIs('hms.ai.predictive-analytics') ? 'active' : '' }}">
                                    <i class="fa fa-chart-line mr-2 w-4"></i> Predictive Analytics
                                </a>
                            </li>
                            <li>
                                <a @click.stop href="{{ route('ai.diagnosis-suggestions') }}" 
                                   class="submenu-link {{ request()->routeIs('ai.diagnosis-suggestions') ? 'active' : '' }}">
                                    <i class="fa fa-notes-medical mr-2 w-4"></i> AI Case Summary Generator
                                </a>
                            </li>
                            <li>
                                <a @click.stop href="{{ route('hms.daily-summary.index') }}" 
                                   class="submenu-link {{ request()->routeIs('hms.daily-summary.*') ? 'active' : '' }}">
                                    <i class="fa fa-calendar-day mr-2 w-4"></i> Daily Summary
                                </a>
                            </li>
                        @endcan

                        {{-- Integrations --}}
                        <li>
                            <div class="nested-menu-item" @click="toggleMenu('integrations')">
                                <div class="flex items-center text-sm">
                                    <i class="fa fa-plug mr-2 w-4 text-cyan-600"></i>
                                    <span>Integrations</span>
                                </div>
                                <i class="fa fa-chevron-down text-xs transition-transform" 
                                   :class="isMenuOpen('integrations') ? 'rotate-180' : ''"></i>
                            </div>
                            <ul x-show="isMenuOpen('integrations')" x-transition class="nested-submenu">
                                <li>
                                    <a @click.stop href="{{ route('hms.integrations.index') }}" 
                                       class="nested-link {{ request()->routeIs('hms.integrations.index') ? 'active' : '' }}">
                                        <i class="fa fa-list mr-2 w-4"></i> All Integrations
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.integrations.payment-gateways') }}" 
                                       class="nested-link {{ request()->routeIs('hms.integrations.payment-gateways') ? 'active' : '' }}">
                                        <i class="fa fa-money-bill-wave mr-2 w-4"></i> M-Pesa / Stripe / PayPal
                                    </a>
                                </li>
                                @can('use telemedicine')
                                    <li>
                                        <a @click.stop href="{{ route('telemedicine.index') }}" 
                                           class="nested-link {{ request()->routeIs('telemedicine.*') ? 'active' : '' }}">
                                            <i class="fa fa-video mr-2 w-4"></i> Zoom Telemedicine
                                        </a>
                                    </li>
                                @endcan
                                <li>
                                    <a @click.stop href="{{ route('hms.integrations.whatsapp') }}" 
                                       class="nested-link {{ request()->routeIs('hms.integrations.whatsapp') ? 'active' : '' }}">
                                        <i class="fab fa-whatsapp mr-2 w-4"></i> WhatsApp API
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.integrations.google-calendar') }}" 
                                       class="nested-link {{ request()->routeIs('hms.integrations.google-calendar') ? 'active' : '' }}">
                                        <i class="fab fa-google mr-2 w-4"></i> Google Calendar
                                    </a>
                                </li>
                                <li>
                                    <a @click.stop href="{{ route('hms.integration.ehr.index') }}" 
                                       class="nested-link {{ request()->routeIs('hms.integration.ehr.*') ? 'active' : '' }}">
                                        <i class="fa fa-network-wired mr-2 w-4"></i> EHR Integration (HL7/FHIR)
                                        <span class="badge badge-warning ml-2">Premium</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a @click.stop href="{{ route('hms.integrations.alerts') }}" 
                               class="submenu-link {{ request()->routeIs('hms.integrations.alerts') ? 'active' : '' }}">
                                <i class="fa fa-bell-slash mr-2 w-4"></i> Automated Alerts & Reminders
                            </a>
                        </li>
                        @can('manage rfid tags')
                            <li>
                                <a @click.stop href="{{ route('rfid.index') }}" 
                                   class="submenu-link {{ request()->routeIs('rfid.*') ? 'active' : '' }}">
                                    <i class="fa fa-tag mr-2 w-4"></i> RFID Management
                                </a>
                            </li>
                        @endcan
                        @can('monitor iot sensors')
                            <li>
                                <a @click.stop href="{{ route('iot.bed-monitoring') }}" 
                                   class="submenu-link {{ request()->routeIs('iot.*') ? 'active' : '' }}">
                                    <i class="fa fa-microchip mr-2 w-4"></i> IoT Monitoring
                                </a>
                            </li>
                        @endcan
                        <li>
                            <a @click.stop href="{{ route('hms.integrations.data-sync') }}" 
                               class="submenu-link {{ request()->routeIs('hms.integrations.data-sync') ? 'active' : '' }}">
                                <i class="fa fa-sync mr-2 w-4"></i> Data Sync / Backup Scheduler
                            </a>
                        </li>
                    </ul>
                </li>
            @endcanany

            </ul>
        </nav>
        
        <!-- Sidebar Footer -->
        <div class="sidebar-footer border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-3 flex-shrink-0" style="height: 60px;">
            <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400">
                <div class="flex items-center">
                    <i class="fa fa-circle text-green-500 mr-2 animate-pulse"></i>
                    <span>System Online</span>
                </div>
                <span>v2.0.1</span>
            </div>
        </div>
    </div>
</div>
