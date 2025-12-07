@extends('layouts.app')

@section('title', 'Edit Marketing - iManagement')

@section('content')
    <div class="flex min-h-screen bg-gradient-to-br from-gray-50 to-blue-50" x-data="{ sidebarOpen: false }">
        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <!-- Sidebar Component -->
        <x-admin.sidebar activeMenu="affiliate.marketing" />

        <!-- Main Wrapper -->
        <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">
            <!-- Header Component -->
            <x-admin.header />

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <div class="max-w-4xl mx-auto space-y-4">
                    <!-- Page Header -->
                    <div class="bg-white rounded-xl shadow-lg p-4 lg:p-6">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.affiliate.marketing.index') }}"
                                class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                                <i class="fa-solid fa-arrow-left text-gray-600"></i>
                            </a>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-orange-100 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-user-pen text-orange-600 text-lg lg:text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl lg:text-2xl font-bold text-gray-800">Edit Marketing</h2>
                                    <p class="text-gray-600 text-xs lg:text-sm mt-0.5">Perbarui data marketing {{ $marketing->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Referral Code Card -->
                    <div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-xl shadow-lg p-4 lg:p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-400 text-sm font-medium mb-1">Kode Referral</p>
                                <p class="text-white text-2xl font-bold tracking-wide">{{ $marketing->referral_code }}</p>
                            </div>
                            <div class="w-16 h-16 rounded-full bg-white bg-opacity-10 flex items-center justify-center">
                                <i class="fa-solid fa-qrcode text-white text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-white rounded-xl shadow-lg p-4 lg:p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Total Cluster</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $marketing->getTotalClusters() }}</p>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="fa-solid fa-building text-blue-600"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-lg p-4 lg:p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Total Revenue</p>
                                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($marketing->getTotalRevenue(), 0, ',', '.') }}</p>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="fa-solid fa-money-bill-wave text-green-600"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-lg p-4 lg:p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Total Komisi</p>
                                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($marketing->getTotalCommission(), 0, ',', '.') }}</p>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                                    <i class="fa-solid fa-hand-holding-dollar text-yellow-600"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Card -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-orange-600 to-red-600 px-6 py-4">
                            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                <i class="fa-solid fa-circle-info"></i>
                                <span>Informasi Marketing</span>
                            </h3>
                        </div>

                        <form action="{{ route('admin.affiliate.marketing.update', $marketing) }}" method="POST" class="p-6">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
                                <!-- Nama Lengkap -->
                                <div class="md:col-span-2">
                                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fa-solid fa-user mr-1 text-orange-600"></i> Nama Lengkap
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition @error('name') border-red-500 @enderror"
                                        id="name" name="name" value="{{ old('name', $marketing->name) }}" required>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- No Telepon -->
                                <div>
                                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fa-solid fa-phone mr-1 text-orange-600"></i> No Telepon
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition @error('phone') border-red-500 @enderror"
                                        id="phone" name="phone" value="{{ old('phone', $marketing->phone) }}" required>
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fa-solid fa-envelope mr-1 text-orange-600"></i> Email
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition @error('email') border-red-500 @enderror"
                                        id="email" name="email" value="{{ old('email', $marketing->email) }}" required>
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- No KTP -->
                                <div>
                                    <label for="id_card_number" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fa-solid fa-id-card mr-1 text-orange-600"></i> No KTP/Identitas
                                    </label>
                                    <input type="text"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition @error('id_card_number') border-red-500 @enderror"
                                        id="id_card_number" name="id_card_number" value="{{ old('id_card_number', $marketing->id_card_number) }}">
                                    @error('id_card_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Join Date -->
                                <div>
                                    <label for="join_date" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fa-solid fa-calendar-days mr-1 text-orange-600"></i> Tanggal Bergabung
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition @error('join_date') border-red-500 @enderror"
                                        id="join_date" name="join_date" value="{{ old('join_date', $marketing->join_date ? \Carbon\Carbon::parse($marketing->join_date)->format('Y-m-d') : '') }}" required>
                                    @error('join_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fa-solid fa-toggle-on mr-1 text-orange-600"></i> Status
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition @error('status') border-red-500 @enderror"
                                        id="status" name="status" required>
                                        <option value="Active" {{ old('status', $marketing->status) === 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Inactive" {{ old('status', $marketing->status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="Suspended" {{ old('status', $marketing->status) === 'Suspended' ? 'selected' : '' }}>Suspended</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Alamat -->
                                <div class="md:col-span-2">
                                    <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fa-solid fa-location-dot mr-1 text-orange-600"></i> Alamat Lengkap
                                    </label>
                                    <textarea
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition @error('address') border-red-500 @enderror"
                                        id="address" name="address" rows="3">{{ old('address', $marketing->address) }}</textarea>
                                    @error('address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-3 justify-end mt-6 pt-6 border-t border-gray-200">
                                <a href="{{ route('admin.affiliate.marketing.index') }}"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-semibold">
                                    <i class="fa-solid fa-times"></i>
                                    <span>Batal</span>
                                </a>
                                <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-lg hover:shadow-lg transition-all font-semibold">
                                    <i class="fa-solid fa-save"></i>
                                    <span>Update Marketing</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>

            <!-- Footer Component -->
            <x-admin.footer />
        </div>
    </div>
@endsection
