@extends('layouts.app')

@section('title', 'Detail Cluster - ' . $cluster->name)

@push('styles')
<style>
    [x-cloak] { 
        display: none !important; 
    }
    
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    .badge-status {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }
    
    /* Modal Styles */
    .modal {
        transition: opacity 0.25s ease;
    }
    .modal-content {
        animation: slideDown 0.3s ease;
    }
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-green-50 py-8 px-4" 
     x-data="clusterDetailApp()" 
     x-init="init()">
    
    <div class="max-w-7xl mx-auto">
        
        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-start gap-4">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        @if($cluster->logo)
                            <img src="{{ Storage::url($cluster->logo) }}" alt="Logo" class="w-20 h-20 rounded-lg object-cover border-2 border-blue-200">
                        @else
                            <div class="w-20 h-20 rounded-lg bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center">
                                <i class="fa-solid fa-building text-white text-3xl"></i>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Info -->
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">{{ $cluster->name }}</h1>
                            @if($cluster->active_flag)
                                <span class="badge-status px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                                    <i class="fa-solid fa-circle-check mr-1"></i> Aktif
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">
                                    <i class="fa-solid fa-circle-xmark mr-1"></i> Tidak Aktif
                                </span>
                            @endif
                        </div>
                        
                        @if($cluster->description)
                            <p class="text-gray-600 mb-2">{{ $cluster->description }}</p>
                        @endif
                        
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-phone text-blue-600"></i>
                                <span>{{ $cluster->phone }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-envelope text-blue-600"></i>
                                <span>{{ $cluster->email }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-calendar text-blue-600"></i>
                                <span>Dibuat: {{ $cluster->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex gap-2">
                    <button @click="openBasicInfoModal" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                        <i class="fa-solid fa-edit mr-1"></i> Edit Info Dasar
                    </button>
                    <a href="{{ route('admin.clusters.index') }}" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="stat-card rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm mb-1">Kantor</p>
                        <h3 class="text-3xl font-bold" x-text="offices.length">{{ $cluster->offices->count() }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class="fa-solid fa-building text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card rounded-xl shadow-lg p-6 text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-pink-100 text-sm mb-1">Karyawan</p>
                        <h3 class="text-3xl font-bold" x-text="employees.length">{{ $cluster->employees->count() }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class="fa-solid fa-users text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card rounded-xl shadow-lg p-6 text-white" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm mb-1">Security</p>
                        <h3 class="text-3xl font-bold" x-text="securities.length">{{ $cluster->securities->count() }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class="fa-solid fa-shield-halved text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card rounded-xl shadow-lg p-6 text-white" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm mb-1">Warga</p>
                        <h3 class="text-3xl font-bold">{{ $cluster->residents->count() }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class="fa-solid fa-house-user text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="bg-white rounded-t-xl shadow-lg">
            <!-- Tab Headers -->
            <div class="border-b border-gray-200">
                <nav class="flex flex-wrap -mb-px px-6" aria-label="Tabs">
                    <button @click="activeTab = 'offices'" 
                            :class="activeTab === 'offices' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-4 px-6 text-sm font-semibold border-b-2 transition-colors duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-building"></i>
                        Kantor (<span x-text="offices.length">{{ $cluster->offices->count() }}</span>)
                    </button>
                    <button @click="activeTab = 'patrols'" 
                            :class="activeTab === 'patrols' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-4 px-6 text-sm font-semibold border-b-2 transition-colors duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-route"></i>
                        Patroli (<span x-text="patrols.length">{{ $cluster->patrols->count() }}</span>)
                    </button>
                    <button @click="activeTab = 'banks'" 
                            :class="activeTab === 'banks' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-4 px-6 text-sm font-semibold border-b-2 transition-colors duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-building-columns"></i>
                        Rekening Bank (<span x-text="banks.length">{{ $cluster->bankAccounts->count() }}</span>)
                    </button>
                    <button @click="activeTab = 'employees'" 
                            :class="activeTab === 'employees' ? 'border-pink-500 text-pink-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-4 px-6 text-sm font-semibold border-b-2 transition-colors duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-users"></i>
                        Karyawan (<span x-text="employees.length">{{ $cluster->employees->count() }}</span>)
                    </button>
                    <button @click="activeTab = 'securities'" 
                            :class="activeTab === 'securities' ? 'border-cyan-500 text-cyan-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-4 px-6 text-sm font-semibold border-b-2 transition-colors duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved"></i>
                        Security (<span x-text="securities.length">{{ $cluster->securities->count() }}</span>)
                    </button>
                    <button @click="activeTab = 'residents'" 
                            :class="activeTab === 'residents' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-4 px-6 text-sm font-semibold border-b-2 transition-colors duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-house-user"></i>
                        Warga ({{ $cluster->residents->count() }})
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                
                <!-- Offices Tab -->
                @include('admin.clusters.partials.tab-offices')

                <!-- Patrols Tab -->
                @include('admin.clusters.partials.tab-patrols')

                <!-- Banks Tab -->
                @include('admin.clusters.partials.tab-banks')

                <!-- Employees Tab -->
                @include('admin.clusters.partials.tab-employees')

                <!-- Securities Tab -->
                @include('admin.clusters.partials.tab-securities')

                <!-- Residents Tab -->
                @include('admin.clusters.partials.tab-residents')

            </div>
        </div>

    </div>

    <!-- Modals will be included here -->
    <!-- Modal Basic Info -->
    <div x-show="showBasicInfoModal" 
         x-cloak
         @click.self="showBasicInfoModal = false"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 rounded-t-xl">
                <h3 class="text-xl font-bold text-white">Edit Info Dasar Cluster</h3>
            </div>
            
            <form @submit.prevent="saveBasicInfo" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Cluster *</label>
                    <input type="text" x-model="basicInfoForm.name" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                    <textarea x-model="basicInfoForm.description" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Telepon *</label>
                        <input type="text" x-model="basicInfoForm.phone" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                        <input type="email" x-model="basicInfoForm.email" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Radius Check-In (meter)</label>
                        <input type="number" x-model="basicInfoForm.radius_checkin" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Radius Patrol (meter)</label>
                        <input type="number" x-model="basicInfoForm.radius_patrol" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" x-model="basicInfoForm.active_flag" class="rounded">
                        <span class="text-sm font-semibold text-gray-700">Cluster Aktif</span>
                    </label>
                </div>
                
                <div class="flex gap-3 pt-4 border-t">
                    <button type="submit" 
                        class="flex-1 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                        <i class="fa-solid fa-save mr-1"></i> Simpan
                    </button>
                    <button type="button" @click="showBasicInfoModal = false"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Placeholder for other modals - will be created as needed --}}
    {{-- @include('admin.clusters.partials.modal-office') --}}
    {{-- @include('admin.clusters.partials.modal-patrol') --}}
    {{-- @include('admin.clusters.partials.modal-bank') --}}
    {{-- @include('admin.clusters.partials.modal-employee') --}}
    {{-- @include('admin.clusters.partials.modal-security') --}}
    
</div>

@push('scripts')
<script>
function clusterDetailApp() {
    return {
        activeTab: 'offices',
        offices: @json($cluster->offices),
        patrols: @json($cluster->patrols),
        banks: @json($cluster->bankAccounts),
        employees: @json($cluster->employees->load('employee')),
        securities: @json($cluster->securities->load('security')),
        
        // Modal states
        showBasicInfoModal: false,
        showOfficeModal: false,
        showPatrolModal: false,
        showBankModal: false,
        showEmployeeModal: false,
        showSecurityModal: false,
        
        // Form data
        basicInfoForm: {
            name: '{{ $cluster->name }}',
            description: '{{ $cluster->description }}',
            phone: '{{ $cluster->phone }}',
            email: '{{ $cluster->email }}',
            radius_checkin: {{ $cluster->radius_checkin ?? 50 }},
            radius_patrol: {{ $cluster->radius_patrol ?? 100 }},
            active_flag: {{ $cluster->active_flag ? 'true' : 'false' }}
        },
        
        officeForm: {
            id: null,
            name: '',
            type_id: 1,
            latitude: '',
            longitude: '',
            active_flag: true
        },
        
        patrolForm: {
            id: null,
            day_type_id: 1,
            pinpoints: []
        },
        
        bankForm: {
            id: null,
            bank_type: '',
            account_holder: '',
            account_number: ''
        },
        
        employeeForm: {
            id: null,
            name: '',
            username: '',
            email: '',
            phone: '',
            password: '',
            role: 'ADMIN'
        },
        
        securityForm: {
            id: null,
            name: '',
            username: '',
            email: '',
            phone: '',
            password: ''
        },
        
        // Maps
        officeMap: null,
        officeMarker: null,
        patrolMap: null,
        patrolMarkers: [],
        
        init() {
            console.log('Cluster Detail App Initialized');
        },
        
        // ==================== BASIC INFO ====================
        openBasicInfoModal() {
            this.showBasicInfoModal = true;
        },
        
        async saveBasicInfo() {
            try {
                const response = await fetch('{{ route("admin.clusters.basic-info.update", $cluster->id) }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(this.basicInfoForm)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonColor: '#10b981'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: error.message,
                    confirmButtonColor: '#ef4444'
                });
            }
        },
        
        // Continue with other CRUD methods...
        // (The file is too long, will split into partials)
    }
}
</script>
@endpush
@endsection
