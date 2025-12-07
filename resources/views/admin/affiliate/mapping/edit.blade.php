@extends('layouts.app')

@section('title', 'Edit Marketing Mapping')

@section('content')
    <div class="flex min-h-screen bg-gradient-to-br from-gray-50 to-blue-50" x-data="{ sidebarOpen: false }">
        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <!-- Sidebar Component -->
        <x-admin.sidebar activeMenu="affiliate.mapping" />

        <!-- Main Wrapper -->
        <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">
            <!-- Header Component -->
            <x-admin.header />

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <div class="max-w-4xl mx-auto space-y-4">
                    <!-- Page Header -->
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.affiliate.mapping.index') }}"
                            class="px-3 py-2 bg-white rounded-lg shadow hover:shadow-md transition">
                            <i class="fa-solid fa-arrow-left text-gray-600"></i>
                        </a>
                        <div>
                            <h1 class="text-xl lg:text-2xl font-bold text-gray-800 flex items-center gap-2">
                                <i class="fa-solid fa-edit text-pink-600"></i>
                                Edit Marketing Mapping
                            </h1>
                            <p class="text-sm text-gray-600 mt-1">Update mapping marketing dengan cluster</p>
                        </div>
                    </div>

                    <!-- Form -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <form id="mappingForm" action="{{ route('admin.affiliate.mapping.update', $mapping) }}" method="POST" onsubmit="return confirmUpdate(event)">
                            @csrf
                            @method('PUT')

                            <div class="space-y-4">
                                <!-- Marketing (Read Only) -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Marketing
                                    </label>
                                    <div class="px-4 py-2 border border-gray-200 bg-gray-50 rounded-lg text-gray-700">
                                        {{ $mapping->marketing->code ?? '-' }} - {{ $mapping->marketing->name ?? '-' }}
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Marketing tidak dapat diubah setelah mapping dibuat</p>
                                </div>

                                <!-- Cluster (Read Only) -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Cluster
                                    </label>
                                    <div class="px-4 py-2 border border-gray-200 bg-gray-50 rounded-lg text-gray-700">
                                        {{ $mapping->cluster->name ?? '-' }}
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Cluster tidak dapat diubah setelah mapping dibuat</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Join Date -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Tanggal Join <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date" name="join_date" value="{{ old('join_date', $mapping->join_date ? \Carbon\Carbon::parse($mapping->join_date)->format('Y-m-d') : '') }}" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('join_date') border-red-500 @enderror">
                                        @error('join_date')
                                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Status -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Status <span class="text-red-500">*</span>
                                        </label>
                                        <select name="status" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('status') border-red-500 @enderror">
                                            <option value="Active" {{ old('status', $mapping->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                            <option value="Completed" {{ old('status', $mapping->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="Cancelled" {{ old('status', $mapping->status) == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        @error('status')
                                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Commission Settings -->
                                <div class="bg-pink-50 border-l-4 border-pink-500 rounded-lg p-4">
                                    <h3 class="font-semibold text-pink-900 mb-3 flex items-center gap-2">
                                        <i class="fa-solid fa-percent"></i>
                                        Pengaturan Komisi
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Commission Percentage -->
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                Persentase Komisi (%) <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number" name="commission_percentage" value="{{ old('commission_percentage', $mapping->commission_percentage) }}" 
                                                required min="0" max="100" step="0.01"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('commission_percentage') border-red-500 @enderror"
                                                placeholder="0.00">
                                            @error('commission_percentage')
                                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                            @enderror
                                            <p class="text-xs text-gray-500 mt-1">Contoh: 10 untuk 10%</p>
                                        </div>

                                        <!-- Commission Amount -->
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                Nominal Komisi (Rp) <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number" name="commission_amount" value="{{ old('commission_amount', $mapping->commission_amount) }}" 
                                                required min="0" step="1000"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('commission_amount') border-red-500 @enderror"
                                                placeholder="0">
                                            @error('commission_amount')
                                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                            @enderror
                                            <p class="text-xs text-gray-500 mt-1">Nominal tetap atau hasil dari persentase</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Catatan
                                    </label>
                                    <textarea name="notes" rows="3"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('notes') border-red-500 @enderror"
                                        placeholder="Catatan tambahan untuk mapping ini...">{{ old('notes', $mapping->notes) }}</textarea>
                                    @error('notes')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                                    <button type="submit"
                                        class="flex-1 px-6 py-3 bg-gradient-to-r from-pink-600 to-rose-600 text-white rounded-lg hover:shadow-lg transition font-semibold">
                                        <i class="fa-solid fa-save mr-1"></i> Update Mapping
                                    </button>
                                    <a href="{{ route('admin.affiliate.mapping.index') }}"
                                        class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold text-center">
                                        <i class="fa-solid fa-times mr-1"></i> Batal
                                    </a>
                                </div>
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
        function confirmUpdate(event) {
            event.preventDefault();
            
            Swal.fire({
                title: 'Update Mapping?',
                text: "Pastikan semua perubahan sudah benar!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#db2777',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Update!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('mappingForm').submit();
                }
            });
            
            return false;
        }

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#db2777',
                timer: 2000,
                timerProgressBar: true
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#db2777'
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error!',
                html: '<ul style="text-align: left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                confirmButtonColor: '#db2777'
            });
        @endif
    </script>
@endsection
