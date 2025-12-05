@extends('layouts.app')

@section('title', 'Data Marketing - Affiliate Management')

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
                <div class="w-full space-y-4 lg:space-y-6">
                    <!-- Page Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h1 class="text-xl lg:text-2xl font-bold text-gray-800 flex items-center gap-2">
                                <i class="fa-solid fa-users text-pink-600"></i>
                                Data Marketing
                            </h1>
                            <p class="text-sm text-gray-600 mt-1">Manajemen data marketing & affiliate</p>
                        </div>
                        <a href="{{ route('admin.affiliate.marketing.create') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-pink-600 to-rose-600 text-white rounded-lg hover:shadow-lg transition-all">
                            <i class="fa-solid fa-plus"></i>
                            <span>Tambah Marketing</span>
                        </a>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">
                        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-blue-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Total Marketing</p>
                                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalMarketing) }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="fa-solid fa-users text-blue-600"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-green-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Marketing Aktif</p>
                                    <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($activeMarketing) }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="fa-solid fa-user-check text-green-600"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-indigo-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Total Revenue</p>
                                    <p class="text-lg lg:text-xl font-bold text-indigo-600 mt-1">Rp {{ number_format($totalRevenue/1000000, 1) }}M</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <i class="fa-solid fa-money-bill-wave text-indigo-600"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-amber-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Total Komisi</p>
                                    <p class="text-lg lg:text-xl font-bold text-amber-600 mt-1">Rp {{ number_format($totalCommission/1000000, 1) }}M</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                                    <i class="fa-solid fa-hand-holding-usd text-amber-600"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                            <div class="flex items-center">
                                <i class="fa-solid fa-check-circle text-green-500 text-xl mr-3"></i>
                                <p class="text-green-700 font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Search & Filter -->
                    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border border-gray-100">
                        <form method="GET">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Cari Marketing</label>
                                    <input type="text" name="search" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent" 
                                           placeholder="Nama, Telepon, Email, atau Kode Referral..." value="{{ request('search') }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                                        <option value="">Semua Status</option>
                                        <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Inactive" {{ request('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="Suspended" {{ request('status') === 'Suspended' ? 'selected' : '' }}>Suspended</option>
                                    </select>
                                </div>
                                <div class="flex items-end gap-2">
                                    <button type="submit" class="flex-1 px-6 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition">
                                        <i class="fa-solid fa-search mr-2"></i>Cari
                                    </button>
                                    <a href="{{ route('admin.affiliate.marketing.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                                        <i class="fa-solid fa-redo"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Data Table -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Marketing</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kode Referral</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kontak</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Cluster Affiliate</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Total Cluster</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($marketings as $marketing)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-rose-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                        {{ strtoupper(substr($marketing->name, 0, 2)) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-semibold text-gray-800">{{ $marketing->name }}</div>
                                                        <div class="text-xs text-gray-500">{{ $marketing->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-mono">{{ $marketing->referral_code }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                <i class="fa-solid fa-phone-alt mr-2 text-gray-400"></i>{{ $marketing->phone }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $marketing->cluster_affiliate_name }}</td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">{{ $marketing->getTotalClusters() }} Cluster</span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="px-3 py-1 {{ $marketing->getStatusBadgeClass() }} rounded-full text-xs font-semibold">{{ $marketing->status }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <a href="{{ route('admin.affiliate.marketing.edit', $marketing) }}" 
                                                       class="px-3 py-1.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-sm">
                                                        <i class="fa-solid fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.affiliate.marketing.destroy', $marketing) }}" 
                                                          method="POST" class="inline"
                                                          onsubmit="return confirm('Yakin ingin menghapus marketing ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="px-3 py-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-12 text-center">
                                                <i class="fa-solid fa-inbox text-gray-300 text-5xl mb-3"></i>
                                                <p class="text-gray-400 font-medium">Belum ada data marketing</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($marketings->hasPages())
                            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                                {{ $marketings->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection
