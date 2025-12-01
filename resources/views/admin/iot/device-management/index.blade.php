@extends('layouts.app')

@section('title', 'Device Management - IoT Monitoring')

@section('content')
    <div class="flex min-h-screen bg-gradient-to-br from-gray-50 to-blue-50" x-data="{ sidebarOpen: false }">
        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <!-- Sidebar Component -->
        <x-admin.sidebar activeMenu="iot-device-management" />

        <!-- Main Wrapper -->
        <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">
            <!-- Header Component -->
            <x-admin.header />

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <div class="w-full space-y-4 lg:space-y-6">
                    <!-- Page Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h1 class="text-xl lg:text-2xl font-bold text-gray-800 flex items-center gap-2">
                                <i class="fa-solid fa-microchip text-blue-600"></i>
                                Device Management
                            </h1>
                            <p class="text-sm text-gray-600 mt-1">Kelola master data IoT devices untuk monitoring keamanan</p>
                        </div>
                        <a href="{{ route('admin.iot.device-management.create') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:shadow-lg transition-all">
                            <i class="fa-solid fa-plus"></i>
                            <span>Tambah Device</span>
                        </a>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 lg:gap-4">
                        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-blue-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Total Devices</p>
                                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total'] }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="fa-solid fa-microchip text-blue-600"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-green-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Active</p>
                                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['active'] }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="fa-solid fa-check-circle text-green-600"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-yellow-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Inactive</p>
                                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['inactive'] }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                    <i class="fa-solid fa-pause-circle text-yellow-600"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-red-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Rusak</p>
                                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['rusak'] }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                    <i class="fa-solid fa-exclamation-triangle text-red-600"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-emerald-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Connected</p>
                                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['connected'] }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                                    <i class="fa-solid fa-wifi text-emerald-600"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters & Search -->
                    <div class="bg-white rounded-xl shadow-md p-4">
                        <form method="GET" action="{{ route('admin.iot.device-management.index') }}" class="space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                                <!-- Search -->
                                <div class="lg:col-span-2">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Cari kode, nama, atau tipe device..."
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                </div>

                                <!-- Hardware Status Filter -->
                                <div>
                                    <select name="hardware_status"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <option value="">Semua Status H/W</option>
                                        <option value="Active" {{ request('hardware_status') == 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Inactive" {{ request('hardware_status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="Rusak" {{ request('hardware_status') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                    </select>
                                </div>

                                <!-- Network Status Filter -->
                                <div>
                                    <select name="network_status"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <option value="">Semua Status Network</option>
                                        <option value="Connected" {{ request('network_status') == 'Connected' ? 'selected' : '' }}>Connected</option>
                                        <option value="Not Connected" {{ request('network_status') == 'Not Connected' ? 'selected' : '' }}>Not Connected</option>
                                    </select>
                                </div>

                                <!-- Cluster Filter -->
                                <div>
                                    <select name="cluster_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <option value="">Semua Cluster</option>
                                        @foreach($clusters as $cluster)
                                            <option value="{{ $cluster->id }}" {{ request('cluster_id') == $cluster->id ? 'selected' : '' }}>
                                                {{ $cluster->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                                    <i class="fa-solid fa-search mr-1"></i> Filter
                                </button>
                                <a href="{{ route('admin.iot.device-management.index') }}"
                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                                    <i class="fa-solid fa-rotate-right mr-1"></i> Reset
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Device Table -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-blue-600 to-blue-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">No</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Kode</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Nama Device</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Cluster</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Status H/W</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Status Network</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($devices as $index => $device)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-4 py-3 text-sm text-gray-700">{{ $devices->firstItem() + $index }}</td>
                                            <td class="px-4 py-3">
                                                <span class="text-sm font-mono font-semibold text-blue-600">{{ $device->code }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-medium text-gray-800">{{ $device->name }}</div>
                                                @if($device->description)
                                                    <div class="text-xs text-gray-500 mt-0.5">{{ Str::limit($device->description, 40) }}</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                                    {{ $device->type }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($device->cluster)
                                                    <div class="text-sm text-gray-800">{{ $device->cluster->name }}</div>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">Belum dipasang</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $device->getStatusBadgeClass() }}">
                                                    {{ $device->hardware_status }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $device->getNetworkBadgeClass() }}">
                                                    {{ $device->network_status }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="flex items-center justify-center gap-1">
                                                    <a href="{{ route('admin.iot.device-management.edit', $device) }}"
                                                        class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 transition text-xs"
                                                        title="Edit">
                                                        <i class="fa-solid fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.iot.device-management.destroy', $device) }}" method="POST" class="inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus device ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="px-2 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition text-xs"
                                                            title="Hapus">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                                <i class="fa-solid fa-inbox text-4xl text-gray-300 mb-2"></i>
                                                <p class="text-sm">Belum ada data device</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($devices->hasPages())
                            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                                {{ $devices->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </main>

            <!-- Footer Component -->
            <x-admin.footer />
        </div>
    </div>
@endsection

@if(session('success'))
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                alert('{{ session('success') }}');
            });
        </script>
    @endpush
@endif
