@extends('layouts.app')

@section('title', 'Wizard: Buat Cluster Baru - iManagement')

@push('styles')
    <style>
        .step-indicator {
            position: relative;
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 1.5rem;
            left: 0;
            right: 0;
            height: 2px;
            background: #e5e7eb;
            z-index: 0;
        }

        .step-item.completed .step-circle {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .step-item.active .step-circle {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
        }

        .step-item.pending .step-circle {
            background: white;
            border: 2px solid #e5e7eb;
            color: #9ca3af;
        }
    </style>
@endpush

@section('content')
    <div class="flex min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-green-50" x-data="clusterWizard()"
        x-init="init()">

        <!-- Main Wrapper - Full Width -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Simple Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 p-4">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.clusters.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition">
                            <i class="fa-solid fa-arrow-left text-gray-600"></i>
                        </a>
                        <div>
                            <h1 class="text-lg lg:text-xl font-bold text-gray-800">Wizard Pendaftaran Cluster</h1>
                            <p class="text-xs text-gray-600">Ikuti langkah-langkah untuk mendaftarkan cluster baru</p>
                        </div>
                    </div>
                    <div class="hidden lg:flex items-center gap-2 text-sm text-gray-600">
                        <i class="fa-solid fa-building text-green-600"></i>
                        <span>iHome Management</span>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-8">
                <div class="max-w-5xl mx-auto">

                    <!-- Progress Indicator -->
                    <div class="bg-white rounded-xl shadow-lg p-6 lg:p-8 mb-6">
                        <div class="step-indicator">
                            <div class="grid grid-cols-3 lg:grid-cols-6 gap-2 lg:gap-4 relative z-10">
                                <!-- Step 1 -->
                                <div class="step-item flex flex-col items-center"
                                    :class="currentStep === 1 ? 'active' : currentStep > 1 ? 'completed' : 'pending'">
                                    <div
                                        class="step-circle w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm mb-2 transition-all">
                                        <i class="fa-solid" :class="currentStep > 1 ? 'fa-check' : 'fa-info-circle'"></i>
                                    </div>
                                    <span class="text-xs text-center font-medium text-gray-700">Info Dasar</span>
                                </div>

                                <!-- Step 2 -->
                                <div class="step-item flex flex-col items-center"
                                    :class="currentStep === 2 ? 'active' : currentStep > 2 ? 'completed' : 'pending'">
                                    <div
                                        class="step-circle w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm mb-2 transition-all">
                                        <i class="fa-solid" :class="currentStep > 2 ? 'fa-check' : 'fa-building'"></i>
                                    </div>
                                    <span class="text-xs text-center font-medium text-gray-700">Kantor</span>
                                </div>

                                <!-- Step 3 -->
                                <div class="step-item flex flex-col items-center"
                                    :class="currentStep === 3 ? 'active' : currentStep > 3 ? 'completed' : 'pending'">
                                    <div
                                        class="step-circle w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm mb-2 transition-all">
                                        <i class="fa-solid" :class="currentStep > 3 ? 'fa-check' : 'fa-route'"></i>
                                    </div>
                                    <span class="text-xs text-center font-medium text-gray-700">Patroli</span>
                                </div>

                                <!-- Step 4 -->
                                <div class="step-item flex flex-col items-center"
                                    :class="currentStep === 4 ? 'active' : currentStep > 4 ? 'completed' : 'pending'">
                                    <div
                                        class="step-circle w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm mb-2 transition-all">
                                        <i class="fa-solid" :class="currentStep > 4 ? 'fa-check' : 'fa-users'"></i>
                                    </div>
                                    <span class="text-xs text-center font-medium text-gray-700">Karyawan</span>
                                </div>

                                <!-- Step 5 -->
                                <div class="step-item flex flex-col items-center"
                                    :class="currentStep === 5 ? 'active' : currentStep > 5 ? 'completed' : 'pending'">
                                    <div
                                        class="step-circle w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm mb-2 transition-all">
                                        <i class="fa-solid" :class="currentStep > 5 ? 'fa-check' : 'fa-shield-halved'"></i>
                                    </div>
                                    <span class="text-xs text-center font-medium text-gray-700">Security</span>
                                </div>

                                <!-- Step 6 -->
                                <div class="step-item flex flex-col items-center"
                                    :class="currentStep === 6 ? 'active' : currentStep > 6 ? 'completed' : 'pending'">
                                    <div
                                        class="step-circle w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm mb-2 transition-all">
                                        <i class="fa-solid"
                                            :class="currentStep > 6 ? 'fa-check' : 'fa-building-columns'"></i>
                                    </div>
                                    <span class="text-xs text-center font-medium text-gray-700">Bank</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Container -->
                    <form @submit.prevent="submitForm" enctype="multipart/form-data">
                        @csrf

                        <!-- Step 1: Basic Information -->
                        <div x-show="currentStep === 1" x-transition class="bg-white rounded-xl shadow-lg p-6 lg:p-8">
                            @include('admin.clusters.wizard.steps.step1-basic')
                        </div>

                        <!-- Step 2: Offices -->
                        <div x-show="currentStep === 2" x-transition class="bg-white rounded-xl shadow-lg p-6 lg:p-8">
                            @include('admin.clusters.wizard.steps.step2-offices')
                        </div>

                        <!-- Step 3: Patrol Points -->
                        <div x-show="currentStep === 3" x-transition class="bg-white rounded-xl shadow-lg p-6 lg:p-8">
                            @include('admin.clusters.wizard.steps.step3-patrols')
                        </div>

                        <!-- Step 4: Employees -->
                        <div x-show="currentStep === 4" x-transition class="bg-white rounded-xl shadow-lg p-6 lg:p-8">
                            @include('admin.clusters.wizard.steps.step4-employees')
                        </div>

                        <!-- Step 5: Securities -->
                        <div x-show="currentStep === 5" x-transition class="bg-white rounded-xl shadow-lg p-6 lg:p-8">
                            @include('admin.clusters.wizard.steps.step5-securities')
                        </div>

                        <!-- Step 6: Bank Accounts -->
                        <div x-show="currentStep === 6" x-transition class="bg-white rounded-xl shadow-lg p-6 lg:p-8">
                            @include('admin.clusters.wizard.steps.step6-banks')
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="bg-white rounded-xl shadow-lg p-4 lg:p-6 mt-6">
                            <div class="flex items-center justify-between">
                                <button type="button" @click="previousStep" x-show="currentStep > 1"
                                    class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                                    <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
                                </button>

                                <div class="flex-1"></div>

                                <button type="button" @click="nextStep" x-show="currentStep < 6"
                                    class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:shadow-lg transition font-semibold">
                                    Selanjutnya <i class="fa-solid fa-arrow-right ml-1"></i>
                                </button>

                                <button type="submit" x-show="currentStep === 6"
                                    class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:shadow-lg transition font-semibold">
                                    <i class="fa-solid fa-check-circle mr-1"></i> Selesai & Simpan
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </main>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function clusterWizard() {
            return {
                currentStep: 1,
                formData: {
                    // Step 1
                    name: '',
                    description: '',
                    phone: '',
                    email: '',
                    logo: null,
                    picture: null,
                    radius_checkin: 5,
                    radius_patrol: 5,

                    // Step 2
                    offices: [
                        { name: '', type_id: 1, latitude: '', longitude: '' }
                    ],

                    // Step 3
                    patrols: [
                        { day_type_id: 1, pinpoints: [] }
                    ],

                    // Step 4
                    employees: [],

                    // Step 5
                    securities: [],

                    // Step 6
                    bank_accounts: [
                        { account_number: '', account_holder: '', bank_type: 'BCA', bank_code_id: 1 }
                    ]
                },

                init() {
                    console.log('Wizard initialized');
                },

                nextStep() {
                    if (this.validateCurrentStep()) {
                        this.currentStep++;
                    }
                },

                previousStep() {
                    if (this.currentStep > 1) {
                        this.currentStep--;
                    }
                },

                validateCurrentStep() {
                    // Basic validation - you can enhance this
                    return true;
                },

                addOffice() {
                    this.formData.offices.push({ name: '', type_id: 1, latitude: '', longitude: '' });
                },

                removeOffice(index) {
                    this.formData.offices.splice(index, 1);
                },

                addEmployee() {
                    this.formData.employees.push({ name: '', username: '', email: '', phone: '', role: 'ADMIN', password: '' });
                },

                removeEmployee(index) {
                    this.formData.employees.splice(index, 1);
                },

                addSecurity() {
                    this.formData.securities.push({ name: '', username: '', email: '', phone: '', password: '' });
                },

                removeSecurity(index) {
                    this.formData.securities.splice(index, 1);
                },

                addBankAccount() {
                    this.formData.bank_accounts.push({ account_number: '', account_holder: '', bank_type: 'BCA', bank_code_id: 1 });
                },

                removeBankAccount(index) {
                    this.formData.bank_accounts.splice(index, 1);
                },

                submitForm() {
                    // Submit via normal form submission or AJAX
                    this.$el.closest('form').submit();
                }
            }
        }
    </script>
@endpush