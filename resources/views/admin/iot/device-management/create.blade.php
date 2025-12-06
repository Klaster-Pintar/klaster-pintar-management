@extends('layouts.app')

@section('title', 'Tambah Device - IoT Monitoring')

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
                <div class="max-w-4xl mx-auto space-y-4">
                    <!-- Page Header -->
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.iot.device-management.index') }}"
                            class="px-3 py-2 bg-white rounded-lg shadow hover:shadow-md transition">
                            <i class="fa-solid fa-arrow-left text-gray-600"></i>
                        </a>
                        <div>
                            <h1 class="text-xl lg:text-2xl font-bold text-gray-800 flex items-center gap-2">
                                <i class="fa-solid fa-plus-circle text-blue-600"></i>
                                Tambah IoT Device Baru
                            </h1>
                            <p class="text-sm text-gray-600 mt-1">Tambahkan device IoT untuk monitoring keamanan cluster</p>
                        </div>
                    </div>

                    <!-- Form -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <form id="deviceForm" action="{{ route('admin.iot.device-management.store') }}" method="POST" onsubmit="return confirmSave(event)">
                            @csrf

                            <div class="space-y-4">
                                <!-- Kode Device -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Kode Device <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="code" value="{{ old('code') }}" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('code') border-red-500 @enderror"
                                        placeholder="Contoh: IOT-001">
                                    @error('code')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nama Device -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nama Device <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name') }}" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                        placeholder="Contoh: Motion Sensor Gate A">
                                    @error('name')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Type -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Type Device <span class="text-red-500">*</span>
                                        </label>
                                        <select name="type" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror">
                                            <option value="">Pilih Type</option>
                                            <option value="Gate Controller" {{ old('type') == 'Gate Controller' ? 'selected' : '' }}>Gate Controller</option>
                                            <option value="IoT Sirine" {{ old('type') == 'IoT Sirine' ? 'selected' : '' }}>
                                                IoT Sirine</option>
                                        </select>
                                        @error('type')
                                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Cluster -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Cluster
                                        </label>
                                        <select name="cluster_id"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('cluster_id') border-red-500 @enderror">
                                            <option value="">Belum dipasang</option>
                                            @foreach($clusters as $cluster)
                                                <option value="{{ $cluster->id }}" {{ old('cluster_id') == $cluster->id ? 'selected' : '' }}>
                                                    {{ $cluster->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('cluster_id')
                                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Deskripsi -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Deskripsi
                                    </label>
                                    <textarea name="description" rows="3"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                        placeholder="Deskripsi detail tentang device ini...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Hardware Status -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Status Hardware <span class="text-red-500">*</span>
                                        </label>
                                        <select name="hardware_status" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('hardware_status') border-red-500 @enderror">
                                            <option value="Active" {{ old('hardware_status', 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                                            <option value="Inactive" {{ old('hardware_status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                            <option value="Rusak" {{ old('hardware_status') == 'Rusak' ? 'selected' : '' }}>
                                                Rusak</option>
                                        </select>
                                        @error('hardware_status')
                                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Network Status -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Status Network <span class="text-red-500">*</span>
                                        </label>
                                        <select name="network_status" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('network_status') border-red-500 @enderror">
                                            <option value="Connected" {{ old('network_status') == 'Connected' ? 'selected' : '' }}>Connected</option>
                                            <option value="Not Connected" {{ old('network_status', 'Not Connected') == 'Not Connected' ? 'selected' : '' }}>Not Connected</option>
                                        </select>
                                        @error('network_status')
                                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- IP Address -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            IP Address
                                        </label>
                                        <input type="text" name="ip_address" value="{{ old('ip_address') }}"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('ip_address') border-red-500 @enderror"
                                            placeholder="192.168.1.100">
                                        @error('ip_address')
                                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Signal Strength -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Signal Strength (%)
                                        </label>
                                        <input type="number" name="signal_strength" value="{{ old('signal_strength') }}"
                                            min="0" max="100"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('signal_strength') border-red-500 @enderror"
                                            placeholder="85">
                                        @error('signal_strength')
                                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Firmware Version -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Firmware Version
                                        </label>
                                        <input type="text" name="firmware_version" value="{{ old('firmware_version') }}"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('firmware_version') border-red-500 @enderror"
                                            placeholder="v1.2.3">
                                        @error('firmware_version')
                                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Location -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Lokasi Fisik
                                    </label>
                                    <input type="text" name="location" value="{{ old('location') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('location') border-red-500 @enderror"
                                        placeholder="Gate A, Lantai 1">
                                    @error('location')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-3 mt-6 pt-6 border-t">
                                <button type="submit"
                                    class="px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:shadow-lg transition font-medium">
                                    <i class="fa-solid fa-save mr-1"></i> Simpan Device
                                </button>
                                <a href="{{ route('admin.iot.device-management.index') }}"
                                    class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                                    <i class="fa-solid fa-times mr-1"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </main>

            <!-- Footer Component -->
            <x-admin.footer />
        </div>
    </div>

    <!-- SweetAlert2 CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmSave(event) {
            event.preventDefault();
            
            Swal.fire({
                title: 'Simpan Device Baru?',
                text: "Pastikan semua data sudah benar!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deviceForm').submit();
                }
            });
            
            return false;
        }

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#3b82f6'
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error!',
                html: '<ul style="text-align: left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                confirmButtonColor: '#3b82f6'
            });
        @endif
    </script>
@endsection