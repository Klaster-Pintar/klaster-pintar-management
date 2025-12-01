@extends('layouts.app')

@section('title', 'Cluster Management - iManagement')

@section('content')
    <div class="flex min-h-screen bg-gradient-to-br from-gray-50 to-blue-50" x-data="{ sidebarOpen: false }">
        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <!-- Sidebar Component -->
        <x-admin.sidebar activeMenu="master.cluster" />

        <!-- Main Wrapper -->
        <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">
            <!-- Header Component -->
            <x-admin.header />

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <div class="w-full space-y-4">
                    <!-- Page Header -->
                    <div class="bg-white rounded-xl shadow-lg p-4 lg:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-green-100 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-building-circle-check text-green-600 text-lg lg:text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl lg:text-2xl font-bold text-gray-800">Cluster Management</h2>
                                    <p class="text-gray-600 text-xs lg:text-sm mt-0.5">Kelola data cluster iHome</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.clusters.create') }}"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white text-sm rounded-lg hover:shadow-lg transition-all font-semibold">
                                <i class="fa-solid fa-plus-circle"></i>
                                <span>Tambah Cluster</span>
                            </a>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow flex items-center gap-3"
                            x-data="{ show: true }" x-show="show" x-transition>
                            <i class="fa-solid fa-circle-check text-green-500 text-xl"></i>
                            <div class="flex-1">
                                <p class="text-green-800 font-medium">{{ session('success') }}</p>
                            </div>
                            <button @click="show = false" class="text-green-500 hover:text-green-700">
                                <i class="fa-solid fa-xmark text-xl"></i>
                            </button>
                        </div>
                    @endif

                    <!-- Search & Filter Card -->
                    <div class="bg-white rounded-xl shadow-lg p-4 lg:p-6">
                        <form method="GET" action="{{ route('admin.clusters.index') }}" class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <!-- Search -->
                                <div class="sm:col-span-2">
                                    <label class="block text-xs lg:text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fa-solid fa-magnifying-glass mr-1 text-green-600"></i> Cari Cluster
                                    </label>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Nama, email, atau phone..."
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                                </div>

                                <!-- Filter Status -->
                                <div>
                                    <label class="block text-xs lg:text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fa-solid fa-filter mr-1 text-green-600"></i> Status
                                    </label>
                                    <select name="status"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                                        <option value="">Semua Status</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <button type="submit"
                                    class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition flex items-center gap-2 font-semibold">
                                    <i class="fa-solid fa-filter"></i>
                                    <span>Filter</span>
                                </button>
                                <a href="{{ route('admin.clusters.index') }}"
                                    class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition flex items-center gap-2 font-semibold">
                                    <i class="fa-solid fa-rotate-right"></i>
                                    <span>Reset</span>
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Clusters Table -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gradient-to-r from-green-600 to-emerald-600 text-white">
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                            <i class="fa-solid fa-building mr-1"></i> Cluster
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                            <i class="fa-solid fa-address-card mr-1"></i> Kontak
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider">
                                            <i class="fa-solid fa-users mr-1"></i> Staff
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider">
                                            <i class="fa-solid fa-toggle-on mr-1"></i> Status
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider">
                                            <i class="fa-solid fa-cog mr-1"></i> Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($clusters as $cluster)
                                        <tr class="hover:bg-green-50 transition-colors">
                                            <!-- Cluster Info -->
                                            <td class="px-4 py-4">
                                                <div class="flex items-center gap-3">
                                                    <!-- Logo/Picture -->
                                                    <div class="flex-shrink-0">
                                                        @if ($cluster->logo && file_exists(storage_path('app/public/' . $cluster->logo)))
                                                            <img src="{{ asset('storage/' . $cluster->logo) }}" 
                                                                alt="{{ $cluster->name }}"
                                                                class="w-12 h-12 rounded-lg object-contain bg-gray-100 p-1 shadow">
                                                        @else
                                                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow">
                                                                <i class="fa-solid fa-building text-white text-xl"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <!-- Name & Description -->
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-bold text-gray-900 truncate">{{ $cluster->name }}</p>
                                                        <p class="text-xs text-gray-500 truncate">{{ $cluster->description }}</p>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Contact Info -->
                                            <td class="px-4 py-4">
                                                <div class="space-y-1">
                                                    <div class="flex items-center gap-2 text-xs text-gray-700">
                                                        <i class="fa-solid fa-envelope text-green-600 w-4"></i>
                                                        <span class="truncate">{{ $cluster->email }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-2 text-xs text-gray-700">
                                                        <i class="fa-solid fa-phone text-green-600 w-4"></i>
                                                        <span>{{ $cluster->phone }}</span>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Staff Stats -->
                                            <td class="px-4 py-4">
                                                <div class="flex items-center justify-center gap-3">
                                                    <!-- Employees -->
                                                    <div class="text-center">
                                                        <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold text-xs">
                                                            {{ $cluster->employees_count ?? 0 }}
                                                        </div>
                                                        <p class="text-xs text-gray-500 mt-0.5">Emp</p>
                                                    </div>
                                                    <!-- Securities -->
                                                    <div class="text-center">
                                                        <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-orange-100 text-orange-700 font-bold text-xs">
                                                            {{ $cluster->securities_count ?? 0 }}
                                                        </div>
                                                        <p class="text-xs text-gray-500 mt-0.5">Sec</p>
                                                    </div>
                                                    <!-- Offices -->
                                                    <div class="text-center">
                                                        <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-purple-100 text-purple-700 font-bold text-xs">
                                                            {{ $cluster->offices_count ?? 0 }}
                                                        </div>
                                                        <p class="text-xs text-gray-500 mt-0.5">Off</p>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Status -->
                                            <td class="px-4 py-4 text-center">
                                                @if ($cluster->active_flag)
                                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                                        <i class="fa-solid fa-check-circle"></i> Active
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">
                                                        <i class="fa-solid fa-ban"></i> Inactive
                                                    </span>
                                                @endif
                                            </td>

                                            <!-- Actions -->
                                            <td class="px-4 py-4">
                                                <div class="flex items-center justify-center gap-2">
                                                    <a href="{{ route('admin.clusters.show', $cluster) }}"
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                                                        title="Detail">
                                                        <i class="fa-solid fa-eye text-sm"></i>
                                                    </a>
                                                    <a href="{{ route('admin.clusters.edit', $cluster) }}"
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                                                        title="Edit">
                                                        <i class="fa-solid fa-edit text-sm"></i>
                                                    </a>
                                                    <button type="button"
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                                                        title="Delete"
                                                        onclick="confirm('Hapus cluster {{ $cluster->name }}?') && document.getElementById('delete-form-{{ $cluster->id }}').submit()">
                                                        <i class="fa-solid fa-trash text-sm"></i>
                                                    </button>
                                                    <form id="delete-form-{{ $cluster->id }}" 
                                                        action="{{ route('admin.clusters.destroy', $cluster) }}" 
                                                        method="POST" class="hidden">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-12">
                                                <div class="text-center">
                                                    <i class="fa-solid fa-building-slash text-6xl text-gray-300 mb-4"></i>
                                                    <p class="text-gray-500 font-medium mb-4">Belum ada cluster terdaftar</p>
                                                    <a href="{{ route('admin.clusters.create') }}"
                                                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                                                        <i class="fa-solid fa-plus-circle"></i>
                                                        <span>Tambah Cluster Pertama</span>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if ($clusters->hasPages())
                        <div class="bg-white rounded-xl shadow-lg p-4">
                            {{ $clusters->links() }}
                        </div>
                    @endif
                </div>
            </main>

            <!-- Footer Component -->
            <x-admin.footer />
        </div>
    </div>
@endsection
