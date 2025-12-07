@extends('layouts.app')

@section('title', 'Device Tracking - IoT Monitoring')

@section('content')
    <div class="flex min-h-screen bg-gradient-to-br from-gray-50 to-blue-50" x-data="{ sidebarOpen: false }">
        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <!-- Sidebar Component -->
        <x-admin.sidebar activeMenu="iot-device-tracking" />

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
                                <i class="fa-solid fa-satellite-dish text-blue-600"></i>
                                Device Tracking
                            </h1>
                            <p class="text-sm text-gray-600 mt-1">Monitor status real-time IoT devices untuk keamanan
                                cluster</p>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <div class="flex items-center gap-2 px-3 py-2 bg-white rounded-lg shadow">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                <span class="text-gray-700 font-medium">Live Monitoring</span>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-2 lg:grid-cols-6 gap-3 lg:gap-4">
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs opacity-90 uppercase font-semibold mb-1">Total Devices</p>
                                    <p class="text-3xl font-bold">{{ $stats['total_devices'] }}</p>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                    <i class="fa-solid fa-microchip text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs opacity-90 uppercase font-semibold mb-1">H/W Active</p>
                                    <p class="text-3xl font-bold">{{ $stats['hardware_active'] }}</p>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                    <i class="fa-solid fa-check-circle text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs opacity-90 uppercase font-semibold mb-1">H/W Inactive</p>
                                    <p class="text-3xl font-bold">{{ $stats['hardware_inactive'] }}</p>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                    <i class="fa-solid fa-pause-circle text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs opacity-90 uppercase font-semibold mb-1">H/W Rusak</p>
                                    <p class="text-3xl font-bold">{{ $stats['hardware_rusak'] }}</p>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                    <i class="fa-solid fa-exclamation-triangle text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs opacity-90 uppercase font-semibold mb-1">Connected</p>
                                    <p class="text-3xl font-bold">{{ $stats['network_connected'] }}</p>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                    <i class="fa-solid fa-wifi text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl shadow-lg p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs opacity-90 uppercase font-semibold mb-1">Disconnected</p>
                                    <p class="text-3xl font-bold">{{ $stats['network_disconnected'] }}</p>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                    <i class="fa-solid fa-wifi-slash text-2xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="bg-white rounded-xl shadow-md p-4">
                        <form method="GET" action="{{ route('admin.iot.device-tracking.index') }}" class="space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                <!-- Search -->
                                <div>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Cari device..."
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                </div>

                                <!-- Hardware Status -->
                                <div>
                                    <select name="hardware_status"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <option value="">Filter Status H/W</option>
                                        <option value="Active" {{ request('hardware_status') == 'Active' ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="Inactive" {{ request('hardware_status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="Rusak" {{ request('hardware_status') == 'Rusak' ? 'selected' : '' }}>
                                            Rusak</option>
                                    </select>
                                </div>

                                <!-- Network Status -->
                                <div>
                                    <select name="network_status"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <option value="">Filter Status Network</option>
                                        <option value="Connected" {{ request('network_status') == 'Connected' ? 'selected' : '' }}>Connected</option>
                                        <option value="Not Connected" {{ request('network_status') == 'Not Connected' ? 'selected' : '' }}>Not Connected</option>
                                    </select>
                                </div>

                                <!-- Cluster -->
                                <div>
                                    <select name="cluster_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <option value="">Filter Cluster</option>
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
                                    <i class="fa-solid fa-filter mr-1"></i> Terapkan Filter
                                </button>
                                <a href="{{ route('admin.iot.device-tracking.index') }}"
                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                                    <i class="fa-solid fa-rotate-right mr-1"></i> Reset
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Device Cards Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @forelse($devices as $device)
                            <div
                                class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border-t-4 
                                        {{ $device->hardware_status == 'Active' ? 'border-green-500' : ($device->hardware_status == 'Inactive' ? 'border-yellow-500' : 'border-red-500') }}">

                                <!-- Device Header -->
                                <div
                                    class="p-4 bg-gradient-to-r 
                                            {{ $device->hardware_status == 'Active' ? 'from-green-50 to-green-100' : ($device->hardware_status == 'Inactive' ? 'from-yellow-50 to-yellow-100' : 'from-red-50 to-red-100') }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <i
                                                    class="fa-solid fa-microchip text-lg {{ $device->hardware_status == 'Active' ? 'text-green-600' : ($device->hardware_status == 'Inactive' ? 'text-yellow-600' : 'text-red-600') }}"></i>
                                                <span
                                                    class="font-mono text-xs font-semibold text-gray-600">{{ $device->code }}</span>
                                            </div>
                                            <h3 class="font-bold text-gray-800 text-sm line-clamp-1">{{ $device->name }}</h3>
                                            <p class="text-xs text-gray-600 mt-0.5">{{ $device->type }}</p>
                                        </div>

                                        <!-- Status Badge -->
                                        <div class="flex flex-col items-end gap-1">
                                            @if($device->network_status == 'Connected')
                                                <div class="flex items-center gap-1 text-green-600">
                                                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                                    <i class="fa-solid fa-wifi text-sm"></i>
                                                </div>
                                            @else
                                                <div class="flex items-center gap-1 text-red-600">
                                                    <i class="fa-solid fa-wifi-slash text-sm"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Device Body -->
                                <div class="p-4 space-y-3">
                                    <!-- Cluster Info -->
                                    <div class="flex items-center gap-2 text-sm">
                                        <i class="fa-solid fa-building text-gray-400"></i>
                                        <span class="text-gray-700 font-medium">
                                            @if($device->cluster)
                                                {{ $device->cluster->name }}
                                            @else
                                                <span class="text-gray-400 italic">Belum dipasang</span>
                                            @endif
                                        </span>
                                    </div>

                                    <!-- Status Info -->
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="text-gray-600">Status Hardware:</span>
                                            <span
                                                class="px-2 py-1 rounded-full font-semibold {{ $device->getStatusBadgeClass() }}">
                                                {{ $device->hardware_status }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="text-gray-600">Status Network:</span>
                                            <span
                                                class="px-2 py-1 rounded-full font-semibold {{ $device->getNetworkBadgeClass() }}">
                                                {{ $device->network_status }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Signal Strength - Hidden for now --}}
                                    {{-- @if($device->signal_strength)
                                        <div class="space-y-1">
                                            <div class="flex items-center justify-between text-xs">
                                                <span class="text-gray-600">Signal Strength:</span>
                                                <span class="font-semibold {{ $device->getSignalStrengthClass() }}">
                                                    {{ $device->signal_strength }}%
                                                </span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                <div class="h-1.5 rounded-full {{ $device->signal_strength >= 80 ? 'bg-green-600' : ($device->signal_strength >= 50 ? 'bg-yellow-600' : 'bg-red-600') }}"
                                                    style="width: {{ $device->signal_strength }}%"></div>
                                            </div>
                                        </div>
                                    @endif --}}

                                    <!-- Last Connected -->
                                    <div class="pt-2 border-t border-gray-200">
                                        <div class="flex items-center gap-2 text-xs text-gray-600">
                                            <i class="fa-solid fa-clock"></i>
                                            <span>Last: {{ $device->getLastConnectedHuman() }}</span>
                                        </div>
                                        @if($device->ip_address)
                                            <div class="flex items-center gap-2 text-xs text-gray-600 mt-1">
                                                <i class="fa-solid fa-network-wired"></i>
                                                <span class="font-mono">{{ $device->ip_address }}</span>
                                            </div>
                                        @endif
                                        @if($device->location)
                                            <div class="flex items-center gap-2 text-xs text-gray-600 mt-1">
                                                <i class="fa-solid fa-location-dot"></i>
                                                <span>{{ $device->location }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full bg-white rounded-xl shadow-md p-12 text-center">
                                <i class="fa-solid fa-satellite-dish text-6xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500 text-lg font-semibold">Tidak ada device ditemukan</p>
                                <p class="text-gray-400 text-sm mt-2">Ubah filter atau tambahkan device baru</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($devices->hasPages())
                        <div class="bg-white rounded-xl shadow-md p-4">
                            {{ $devices->links() }}
                        </div>
                    @endif
                </div>
            </main>

            <!-- Footer Component -->
            <x-admin.footer />
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto refresh page every 30 seconds for live monitoring
        setTimeout(function () {
            location.reload();
        }, 30000);
    </script>
@endpush