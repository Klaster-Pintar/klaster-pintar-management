@extends('layouts.app')

@section('title', 'Detail Cluster - ' . $cluster->name)

@push('styles')
<style>
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    .info-card {
        transition: all 0.3s ease;
    }
    .info-card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    .badge-status {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-green-50 py-8 px-4">
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
                    <button onclick="openBasicInfoModal({{ json_encode($cluster) }})" 
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
            <!-- Offices -->
            <div class="stat-card rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm mb-1">Kantor</p>
                        <h3 class="text-3xl font-bold">{{ $cluster->offices->count() }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class="fa-solid fa-building text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Employees -->
            <div class="stat-card rounded-xl shadow-lg p-6 text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-pink-100 text-sm mb-1">Karyawan</p>
                        <h3 class="text-3xl font-bold">{{ $cluster->employees->count() }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class="fa-solid fa-users text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Securities -->
            <div class="stat-card rounded-xl shadow-lg p-6 text-white" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm mb-1">Security</p>
                        <h3 class="text-3xl font-bold">{{ $cluster->securities->count() }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class="fa-solid fa-shield-halved text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Residents -->
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
        <div class="bg-white rounded-t-xl shadow-lg" x-data="{ activeTab: 'offices' }">
            <!-- Tab Headers -->
            <div class="border-b border-gray-200">
                <nav class="flex flex-wrap -mb-px px-6" aria-label="Tabs">
                    <button @click="activeTab = 'offices'" 
                            :class="activeTab === 'offices' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-4 px-6 text-sm font-semibold border-b-2 transition-colors duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-building"></i>
                        Kantor ({{ $cluster->offices->count() }})
                    </button>
                    <button @click="activeTab = 'patrols'" 
                            :class="activeTab === 'patrols' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-4 px-6 text-sm font-semibold border-b-2 transition-colors duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-route"></i>
                        Patroli ({{ $cluster->patrols->count() }})
                    </button>
                    <button @click="activeTab = 'banks'" 
                            :class="activeTab === 'banks' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-4 px-6 text-sm font-semibold border-b-2 transition-colors duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-building-columns"></i>
                        Rekening Bank ({{ $cluster->bankAccounts->count() }})
                    </button>
                    <button @click="activeTab = 'employees'" 
                            :class="activeTab === 'employees' ? 'border-pink-500 text-pink-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-4 px-6 text-sm font-semibold border-b-2 transition-colors duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-users"></i>
                        Karyawan ({{ $cluster->employees->count() }})
                    </button>
                    <button @click="activeTab = 'securities'" 
                            :class="activeTab === 'securities' ? 'border-cyan-500 text-cyan-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-4 px-6 text-sm font-semibold border-b-2 transition-colors duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved"></i>
                        Security ({{ $cluster->securities->count() }})
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
                <div x-show="activeTab === 'offices'" x-transition>
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Data Kantor Cluster</h2>
                        <button onclick="openOfficeModal()" 
                           class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition font-semibold">
                            <i class="fa-solid fa-plus mr-1"></i> Tambah Kantor
                        </button>
                    </div>

                    @if($cluster->offices->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b-2 border-gray-200">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Kantor</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Latitude</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Longitude</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($cluster->offices as $index => $office)
                                        <tr class="hover:bg-orange-50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <i class="fa-solid fa-building text-orange-600 mr-2"></i>
                                                    <span class="font-semibold text-gray-900">{{ $office->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">{{ $office->latitude }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">{{ $office->longitude }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @if($office->active_flag)
                                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                                        <i class="fa-solid fa-check-circle mr-1"></i> Aktif
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                                        <i class="fa-solid fa-times-circle mr-1"></i> Tidak Aktif
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <button onclick='openOfficeModal(@json($office))' 
                                                    class="text-blue-600 hover:text-blue-800 mr-2" title="Edit">
                                                    <i class="fa-solid fa-edit text-lg"></i>
                                                </button>
                                                <button onclick="deleteOffice({{ $cluster->id }}, {{ $office->id }}, '{{ addslashes($office->name) }}')" 
                                                    class="text-red-600 hover:text-red-800" title="Hapus">
                                                    <i class="fa-solid fa-trash text-lg"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-lg">
                            <i class="fa-solid fa-building text-gray-300 text-6xl mb-4"></i>
                            <p class="text-gray-500 text-lg font-semibold mb-2">Belum ada kantor ditambahkan</p>
                            <p class="text-gray-400 text-sm mb-4">Tambahkan kantor untuk cluster ini</p>
                            <button onclick="openOfficeModal()" 
                                    class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition font-semibold">
                                <i class="fa-solid fa-plus mr-2"></i> Tambah Kantor
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Patrols Tab -->
                <div x-show="activeTab === 'patrols'" x-transition>
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Rute Patroli</h2>
                        <button onclick="openPatrolModal()" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                            <i class="fa-solid fa-plus mr-1"></i> Tambah Rute
                        </button>
                    </div>

                    @if($cluster->patrols->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b-2 border-gray-200">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Rute</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Jumlah Titik</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Koordinat Titik</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($cluster->patrols as $index => $patrol)
                                        @php
                                            $pinpoints = is_string($patrol->pinpoints) ? json_decode($patrol->pinpoints, true) : $patrol->pinpoints;
                                        @endphp
                                        <tr class="hover:bg-blue-50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <i class="fa-solid fa-route text-blue-600 mr-2"></i>
                                                    <span class="font-semibold text-gray-900">Rute Patroli {{ $index + 1 }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                                    {{ $pinpoints ? count($pinpoints) : 0 }} titik
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                @if($pinpoints && count($pinpoints) > 0)
                                                    <div class="max-w-md">
                                                        @foreach($pinpoints as $idx => $point)
                                                            <span class="inline-block mr-2 mb-1 text-xs">
                                                                <i class="fa-solid fa-map-pin text-blue-500 mr-1"></i>
                                                                ({{ $point['lat'] ?? '-' }}, {{ $point['lng'] ?? '-' }})
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-gray-400 italic">Tidak ada titik patroli</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <button onclick='openPatrolModal(@json($patrol))' 
                                                        class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition mr-2 text-sm font-semibold">
                                                    <i class="fa-solid fa-edit text-lg"></i>
                                                </button>
                                                <button onclick="deletePatrol({{ $cluster->id }}, {{ $patrol->id }})" 
                                                        class="inline-flex items-center px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-semibold">
                                                    <i class="fa-solid fa-trash text-lg"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-lg">
                            <i class="fa-solid fa-route text-gray-300 text-6xl mb-4"></i>
                            <p class="text-gray-500 text-lg font-semibold mb-2">Belum ada rute patroli</p>
                            <p class="text-gray-400 text-sm mb-4">Tambahkan rute patroli untuk cluster ini</p>
                            <button onclick="openPatrolModal()" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                                <i class="fa-solid fa-plus mr-2"></i> Tambah Rute
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Banks Tab -->
                <div x-show="activeTab === 'banks'" x-transition>
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Rekening Bank</h2>
                        <button onclick="openBankModal()" 
                                class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition font-semibold">
                            <i class="fa-solid fa-plus mr-1"></i> Tambah Rekening
                        </button>
                    </div>

                    @if($cluster->bankAccounts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b-2 border-gray-200">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Bank</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Pemegang</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nomor Rekening</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($cluster->bankAccounts as $index => $bank)
                                        <tr class="hover:bg-emerald-50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <i class="fa-solid fa-building-columns text-emerald-600 mr-2"></i>
                                                    <span class="font-semibold text-gray-900">{{ $bank->bank_type }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $bank->account_holder }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <code class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-sm font-mono">{{ $bank->account_number }}</code>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <button onclick='openBankModal(@json($bank))' 
                                                        class="inline-flex items-center px-3 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition mr-2 text-sm font-semibold">
                                                    <i class="fa-solid fa-edit text-lg"></i>
                                                </button>
                                                <button onclick="deleteBank({{ $cluster->id }}, {{ $bank->id }}, '{{ addslashes($bank->bank_type) }}')" 
                                                        class="inline-flex items-center px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-semibold">
                                                    <i class="fa-solid fa-trash text-lg"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-lg">
                            <i class="fa-solid fa-building-columns text-gray-300 text-6xl mb-4"></i>
                            <p class="text-gray-500 text-lg font-semibold mb-2">Belum ada rekening bank</p>
                            <p class="text-gray-400 text-sm mb-4">Tambahkan rekening bank untuk cluster ini</p>
                            <button onclick="openBankModal()" 
                                    class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition font-semibold">
                                <i class="fa-solid fa-plus mr-2"></i> Tambah Rekening
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Employees Tab -->
                <div x-show="activeTab === 'employees'" x-transition>
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Data Karyawan</h2>
                        <button onclick="openEmployeeModal()" 
                                class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition font-semibold">
                            <i class="fa-solid fa-plus mr-1"></i> Tambah Karyawan
                        </button>
                    </div>

                    @if($cluster->employees->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b-2 border-gray-200">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Username</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No. HP</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($cluster->employees as $index => $employee)
                                        <tr class="hover:bg-pink-50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 rounded-full bg-pink-200 flex items-center justify-center mr-3">
                                                        <i class="fa-solid fa-user text-pink-700 text-sm"></i>
                                                    </div>
                                                    <span class="font-semibold text-gray-900">{{ $employee->employee->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $employee->employee->username }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $employee->employee->email ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $employee->employee->phone ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <button onclick='openEmployeeModal(@json($employee))' 
                                                        class="inline-flex items-center px-3 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition mr-2 text-sm font-semibold">
                                                    <i class="fa-solid fa-edit text-lg"></i>
                                                </button>
                                                <button onclick="deleteEmployee({{ $cluster->id }}, {{ $employee->id }}, '{{ addslashes($employee->employee->name) }}')" 
                                                        class="inline-flex items-center px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-semibold">
                                                    <i class="fa-solid fa-trash text-lg"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-lg">
                            <i class="fa-solid fa-users text-gray-300 text-6xl mb-4"></i>
                            <p class="text-gray-500 text-lg font-semibold mb-2">Belum ada karyawan</p>
                            <p class="text-gray-400 text-sm mb-4">Tambahkan karyawan untuk cluster ini</p>
                            <button onclick="openEmployeeModal()" 
                                    class="inline-flex items-center px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition font-semibold">
                                <i class="fa-solid fa-plus mr-2"></i> Tambah Karyawan
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Securities Tab -->
                <div x-show="activeTab === 'securities'" x-transition>
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Data Security</h2>
                        <button onclick="openSecurityModal()" 
                                class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition font-semibold">
                            <i class="fa-solid fa-plus mr-1"></i> Tambah Security
                        </button>
                    </div>

                    @if($cluster->securities->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b-2 border-gray-200">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Username</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No. HP</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($cluster->securities as $index => $security)
                                        <tr class="hover:bg-cyan-50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 rounded-full bg-cyan-200 flex items-center justify-center mr-3">
                                                        <i class="fa-solid fa-shield text-cyan-700 text-sm"></i>
                                                    </div>
                                                    <span class="font-semibold text-gray-900">{{ $security->security->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $security->security->username }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $security->security->email ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $security->security->phone ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <button onclick='openSecurityModal(@json($security))' 
                                                        class="inline-flex items-center px-3 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition mr-2 text-sm font-semibold">
                                                    <i class="fa-solid fa-edit text-lg"></i>
                                                </button>
                                                <button onclick="deleteSecurity({{ $cluster->id }}, {{ $security->id }}, '{{ addslashes($security->security->name) }}')" 
                                                        class="inline-flex items-center px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-semibold">
                                                    <i class="fa-solid fa-trash text-lg"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-lg">
                            <i class="fa-solid fa-shield-halved text-gray-300 text-6xl mb-4"></i>
                            <p class="text-gray-500 text-lg font-semibold mb-2">Belum ada security</p>
                            <p class="text-gray-400 text-sm mb-4">Tambahkan security untuk cluster ini</p>
                            <button onclick="openSecurityModal()" 
                                    class="inline-flex items-center px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition font-semibold">
                                <i class="fa-solid fa-plus mr-2"></i> Tambah Security
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Residents Tab -->
                <div x-show="activeTab === 'residents'" x-transition>
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Data Warga</h2>
                    </div>

                    @if($cluster->residents->count() > 0)
                        <div class="mb-6">
                            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                                <div class="flex items-center">
                                    <i class="fa-solid fa-check-circle text-green-600 text-2xl mr-3"></i>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $cluster->residents->count() }} Warga Terdaftar</p>
                                        <p class="text-sm text-gray-600">Data warga sudah berhasil diupload</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto mb-6">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b-2 border-gray-200">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Blok</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Username</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No. HP</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($cluster->residents as $index => $resident)
                                        <tr class="hover:bg-green-50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">
                                                    {{ $resident->block_number ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 rounded-full bg-green-200 flex items-center justify-center mr-3">
                                                        <i class="fa-solid fa-user text-green-700 text-sm"></i>
                                                    </div>
                                                    <span class="font-semibold text-gray-900">{{ $resident->resident->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $resident->resident->username }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $resident->resident->email ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $resident->resident->phone ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <!-- Upload Section -->
                    <div class="bg-gradient-to-br from-green-50 to-blue-50 border-2 border-dashed border-green-300 rounded-xl p-6">
                        <div class="flex items-start gap-4 mb-6">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center">
                                    <i class="fa-solid fa-upload text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-800 mb-2">Upload Data Warga</h3>
                                <p class="text-sm text-gray-600">Upload file CSV atau Excel untuk menambahkan data warga secara massal</p>
                            </div>
                        </div>

                        <form action="{{ route('admin.clusters.residents.upload', $cluster->id) }}" 
                              method="POST" 
                              enctype="multipart/form-data" 
                              id="uploadResidentForm"
                              class="space-y-4">
                            @csrf
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Pilih File CSV/Excel
                                </label>
                                <input type="file" 
                                       name="resident_file" 
                                       id="residentFile"
                                       accept=".csv,.xlsx,.xls"
                                       required
                                       class="w-full text-sm text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-600 file:text-white hover:file:bg-green-700 cursor-pointer border border-gray-300 rounded-lg">
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="fa-solid fa-info-circle mr-1"></i>
                                    Format: CSV atau Excel (.xlsx, .xls) | Maksimal ukuran: 5MB
                                </p>
                            </div>

                            <div class="flex gap-3">
                                <button type="submit" 
                                        class="flex-1 px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:shadow-lg transition font-semibold">
                                    <i class="fa-solid fa-upload mr-2"></i>
                                    Upload Data Warga
                                </button>
                                <a href="{{ route('admin.clusters.residents.template') }}" 
                                   class="px-6 py-3 bg-white border-2 border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition font-semibold">
                                    <i class="fa-solid fa-download mr-2"></i>
                                    Download Template
                                </a>
                            </div>
                        </form>

                        <!-- Info Format -->
                        <div class="mt-6 bg-white rounded-lg p-4 border border-blue-200">
                            <div class="flex gap-3">
                                <i class="fa-solid fa-lightbulb text-blue-600 text-lg mt-1"></i>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800 mb-2">Format File CSV/Excel:</p>
                                    <ul class="text-sm text-gray-700 space-y-1">
                                        <li class="flex items-start gap-2">
                                            <i class="fa-solid fa-check text-green-600 mt-0.5"></i>
                                            <span>Kolom yang diperlukan: <code class="px-1.5 py-0.5 bg-gray-100 rounded text-xs font-mono">block_number, name, email, phone</code></span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <i class="fa-solid fa-check text-green-600 mt-0.5"></i>
                                            <span>Download template untuk melihat contoh format yang benar</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <i class="fa-solid fa-check text-green-600 mt-0.5"></i>
                                            <span>Data akan otomatis divalidasi sebelum disimpan</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
document.getElementById('uploadResidentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const fileInput = document.getElementById('residentFile');
    
    if (!fileInput.files[0]) {
        Swal.fire({
            icon: 'warning',
            title: 'File Belum Dipilih',
            text: 'Silakan pilih file CSV/Excel terlebih dahulu',
            confirmButtonColor: '#f59e0b'
        });
        return;
    }

    // Show loading
    Swal.fire({
        title: 'Mengupload Data...',
        html: 'Mohon tunggu, sedang memproses file',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Submit via fetch
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                html: `<strong>${result.data.total_imported}</strong> data warga berhasil diimport`,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.reload();
            });
        } else {
            let errorHtml = result.message || 'Gagal mengupload data';
            
            if (result.errors && Array.isArray(result.errors)) {
                errorHtml += '<br><br><div class="text-left text-sm">';
                errorHtml += '<strong>Errors:</strong><br>';
                result.errors.slice(0, 10).forEach(err => {
                    errorHtml += `• ${err}<br>`;
                });
                if (result.errors.length > 10) {
                    errorHtml += `<br>... dan ${result.errors.length - 10} error lainnya`;
                }
                errorHtml += '</div>';
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Upload Gagal',
                html: errorHtml,
                confirmButtonColor: '#ef4444'
            });
        }
    })
    .catch(error => {
        console.error('Upload error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: 'Gagal mengupload file. Silakan coba lagi.',
            confirmButtonColor: '#ef4444'
        });
    });
});
</script>

<!-- Google Maps API - Load FIRST (same as wizard) -->
<script>
    // Global variable for Google Maps API Key
    const GOOGLE_MAPS_API_KEY = '{{ env('GOOGLE_MAPS_API_KEY', '') }}';
    
    // Global flag to check if Maps is ready
    let googleMapsReady = false;
    
    function initMap() {
        googleMapsReady = true;
        console.log('Google Maps API loaded successfully');
        
        // Dispatch event when ready (same event name as wizard)
        window.dispatchEvent(new Event('google-maps-ready'));
    }
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY', '') }}&libraries=places&callback=initMap"></script>

<!-- Include CRUD JavaScript AFTER Maps -->
<script src="{{ asset('js/cluster-detail-crud.js') }}"></script>
@endpush

<!-- MODALS -->
<!-- Basic Info Modal -->
<div id="basicInfoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-white">Edit Info Dasar Cluster</h3>
            <button onclick="closeBasicInfoModal()" class="text-white hover:text-gray-200 text-2xl">&times;</button>
        </div>
        
        <form id="basicInfoForm" class="p-6 space-y-4">
            <input type="hidden" id="basic_cluster_id" value="{{ $cluster->id }}">
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Cluster</label>
                <input type="text" id="basic_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                <textarea id="basic_description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Telepon</label>
                    <input type="text" id="basic_phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" id="basic_email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Radius Checkin (meter)</label>
                    <input type="number" id="basic_radius_checkin" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Radius Patrol (meter)</label>
                    <input type="number" id="basic_radius_patrol" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select id="basic_active_flag" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="Y">Aktif</option>
                    <option value="N">Nonaktif</option>
                </select>
            </div>
            
            <div class="flex justify-end gap-3 pt-4 border-t">
                <button type="button" onclick="closeBasicInfoModal()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Office Modal -->
<div id="officeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-orange-600 to-orange-700 px-6 py-4 flex justify-between items-center">
            <h3 id="officeModalTitle" class="text-xl font-bold text-white">Tambah Kantor</h3>
            <button onclick="closeOfficeModal()" class="text-white hover:text-gray-200 text-2xl">&times;</button>
        </div>
        
        <form id="officeForm" class="p-6 space-y-4">
            <input type="hidden" id="office_id">
            <input type="hidden" id="office_cluster_id" value="{{ $cluster->id }}">
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kantor <span class="text-red-500">*</span></label>
                <input type="text" id="office_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" required>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Lokasi (Seret marker di peta) <span class="text-red-500">*</span></label>
                <div id="officeMap" class="w-full h-96 rounded-lg border border-gray-300 mb-2"></div>
                <div class="flex gap-4 text-sm text-gray-600">
                    <div>Latitude: <span id="office_lat_display">-</span></div>
                    <div>Longitude: <span id="office_lng_display">-</span></div>
                </div>
                <input type="hidden" id="office_lat">
                <input type="hidden" id="office_lng">
            </div>
            
            <div class="flex justify-end gap-3 pt-4 border-t">
                <button type="button" onclick="closeOfficeModal()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition font-semibold">
                    <span id="officeSubmitText">Tambah Kantor</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Patrol Modal -->
<div id="patrolModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 flex justify-between items-center">
            <h3 id="patrolModalTitle" class="text-xl font-bold text-white">Tambah Rute Patroli</h3>
            <button onclick="closePatrolModal()" class="text-white hover:text-gray-200 text-2xl">&times;</button>
        </div>
        
        <form id="patrolForm" class="p-6 space-y-4">
            <input type="hidden" id="patrol_id">
            <input type="hidden" id="patrol_cluster_id" value="{{ $cluster->id }}">
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                <p class="text-sm text-blue-800 font-semibold mb-2">Cara Menambah Titik Patroli:</p>
                <ul class="text-sm text-blue-700 space-y-1 list-disc list-inside">
                    <li>Klik pada peta untuk menambah titik patroli</li>
                    <li>Seret marker untuk mengubah posisi</li>
                    <li>Klik kanan pada marker untuk menghapus</li>
                    <li>Garis biru akan menghubungkan semua titik patroli</li>
                </ul>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Peta Rute Patroli</label>
                <div id="patrolMap" class="w-full h-96 rounded-lg border border-gray-300 mb-2"></div>
                <div class="text-sm text-gray-600">
                    Jumlah Titik: <span id="patrol_point_count" class="font-bold text-blue-600">0</span>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 pt-4 border-t">
                <button type="button" onclick="closePatrolModal()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                    <span id="patrolSubmitText">Simpan Rute</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Bank Modal -->
<div id="bankModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-4 flex justify-between items-center">
            <h3 id="bankModalTitle" class="text-xl font-bold text-white">Tambah Rekening Bank</h3>
            <button onclick="closeBankModal()" class="text-white hover:text-gray-200 text-2xl">&times;</button>
        </div>
        
        <form id="bankForm" class="p-6 space-y-4">
            <input type="hidden" id="bank_id">
            <input type="hidden" id="bank_cluster_id" value="{{ $cluster->id }}">
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Bank <span class="text-red-500">*</span></label>
                <select id="bank_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent" required>
                    <option value="">Pilih Bank</option>
                    <option value="BCA">BCA</option>
                    <option value="BRI">BRI</option>
                    <option value="BNI">BNI</option>
                    <option value="MANDIRI">Mandiri</option>
                    <option value="CIMB">CIMB Niaga</option>
                    <option value="PERMATA">Permata</option>
                    <option value="BTN">BTN</option>
                    <option value="DANAMON">Danamon</option>
                    <option value="BSI">Bank Syariah Indonesia (BSI)</option>
                    <option value="OTHERS">Lainnya</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Bank <span class="text-red-500">*</span></label>
                <input type="number" id="bank_code_id" placeholder="Contoh: 014 (BCA), 002 (BRI)" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent" required>
                <p class="text-xs text-gray-500 mt-1">Kode bank 3 digit (BCA: 014, BRI: 002, BNI: 009, Mandiri: 008)</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pemegang Rekening <span class="text-red-500">*</span></label>
                <input type="text" id="bank_account_holder" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent" required>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Rekening <span class="text-red-500">*</span></label>
                <input type="text" id="bank_account_number" placeholder="Masukkan nomor rekening" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent" required>
            </div>
            
            <div class="flex justify-end gap-3 pt-4 border-t">
                <button type="button" onclick="closeBankModal()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition font-semibold">
                    <span id="bankSubmitText">Tambah Rekening</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Employee Modal -->
<div id="employeeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-pink-600 to-pink-700 px-6 py-4 flex justify-between items-center">
            <h3 id="employeeModalTitle" class="text-xl font-bold text-white">Tambah Karyawan</h3>
            <button onclick="closeEmployeeModal()" class="text-white hover:text-gray-200 text-2xl">&times;</button>
        </div>
        
        <form id="employeeForm" class="p-6 space-y-4">
            <input type="hidden" id="employee_id">
            <input type="hidden" id="employee_cluster_id" value="{{ $cluster->id }}">
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="employee_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent" required>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Username <span class="text-red-500">*</span></label>
                <input type="text" id="employee_username" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent" required>
                <p class="text-xs text-gray-500 mt-1">Username untuk login (tidak bisa diubah setelah dibuat)</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <input type="email" id="employee_email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">No. HP</label>
                <input type="text" id="employee_phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
            </div>
            
            <div id="employee_password_section">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                <input type="password" id="employee_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password (khusus edit)</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Role <span class="text-red-500">*</span></label>
                <select id="employee_role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent" required>
                    <option value="">Pilih Role</option>
                    <option value="RT">RT (Rukun Tetangga)</option>
                    <option value="RW">RW (Rukun Warga)</option>
                    <option value="ADMIN">Admin Cluster</option>
                </select>
            </div>
            
            <div class="flex justify-end gap-3 pt-4 border-t">
                <button type="button" onclick="closeEmployeeModal()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition font-semibold">
                    <span id="employeeSubmitText">Tambah Karyawan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Security Modal -->
<div id="securityModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-cyan-600 to-cyan-700 px-6 py-4 flex justify-between items-center">
            <h3 id="securityModalTitle" class="text-xl font-bold text-white">Tambah Security</h3>
            <button onclick="closeSecurityModal()" class="text-white hover:text-gray-200 text-2xl">&times;</button>
        </div>
        
        <form id="securityForm" class="p-6 space-y-4">
            <input type="hidden" id="security_id">
            <input type="hidden" id="security_cluster_id" value="{{ $cluster->id }}">
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="security_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Username <span class="text-red-500">*</span></label>
                <input type="text" id="security_username" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required>
                <p class="text-xs text-gray-500 mt-1">Username untuk login (tidak bisa diubah setelah dibuat)</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <input type="email" id="security_email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">No. HP</label>
                <input type="text" id="security_phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
            </div>
            
            <div id="security_password_section">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                <input type="password" id="security_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password (khusus edit)</p>
            </div>
            
            <div class="flex justify-end gap-3 pt-4 border-t">
                <button type="button" onclick="closeSecurityModal()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition font-semibold">
                    <span id="securitySubmitText">Tambah Security</span>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
