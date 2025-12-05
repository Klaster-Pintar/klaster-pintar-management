@extends('layouts.app')

@section('title', 'Commission Settings')

@section('content')

    <body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
        <div class="flex min-h-screen" x-data="{ sidebarOpen: false }">
            <!-- Mobile Sidebar Backdrop -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false"
                class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
                x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

            <!-- Sidebar Component -->
            <x-admin.sidebar activeMenu="affiliate.commission" />

            <!-- Main Content -->
            <div class="flex-1 flex flex-col min-h-screen lg:ml-64">
                <!-- Header Component -->
                <x-admin.header />

                <!-- Page Content -->
                <main class="flex-1 p-4 md:p-6 overflow-auto">
                    <div class="max-w-7xl mx-auto space-y-6">
                        <!-- Page Title -->
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center gap-3">
                                    <i class="fas fa-percent text-pink-600"></i>
                                    Commission Settings
                                </h1>
                                <p class="text-gray-500 mt-1">Kelola pengaturan komisi affiliate</p>
                            </div>
                            <a href="{{ route('admin.affiliate.commission.create') }}"
                                class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-pink-600 to-rose-600 text-white rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Setting
                            </a>
                        </div>

                        <!-- Statistics Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div
                                class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
                                <div class="flex items-center gap-4">
                                    <div class="p-3 bg-blue-50 rounded-lg">
                                        <i class="fas fa-cog text-blue-600 text-2xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-sm font-medium">Total Settings</p>
                                        <h3 class="text-2xl font-bold text-gray-800">{{ number_format($totalSettings) }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
                                <div class="flex items-center gap-4">
                                    <div class="p-3 bg-green-50 rounded-lg">
                                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-sm font-medium">Active Settings</p>
                                        <h3 class="text-2xl font-bold text-green-600">{{ number_format($activeSettings) }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
                                <div class="flex items-center gap-4">
                                    <div class="p-3 bg-indigo-50 rounded-lg">
                                        <i class="fas fa-globe text-indigo-600 text-2xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-sm font-medium">Global Settings</p>
                                        <h3 class="text-2xl font-bold text-indigo-600">{{ number_format($globalSettings) }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
                                <div class="flex items-center gap-4">
                                    <div class="p-3 bg-amber-50 rounded-lg">
                                        <i class="fas fa-user-tag text-amber-600 text-2xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-sm font-medium">Specific Settings</p>
                                        <h3 class="text-2xl font-bold text-amber-600">{{ number_format($specificSettings) }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(session('success'))
                            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Filters -->
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Commission Type</label>
                                    <select name="commission_type"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                                        <option value="">All Types</option>
                                        <option value="Percentage" {{ request('commission_type') === 'Percentage' ? 'selected' : '' }}>Percentage</option>
                                        <option value="Fixed" {{ request('commission_type') === 'Fixed' ? 'selected' : '' }}>
                                            Fixed</option>
                                        <option value="Both" {{ request('commission_type') === 'Both' ? 'selected' : '' }}>
                                            Both</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                                    <select name="is_active"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                                        <option value="">All Status</option>
                                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                                <div class="flex items-end gap-2 md:col-span-2">
                                    <button type="submit"
                                        class="flex-1 px-6 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition">
                                        <i class="fas fa-filter mr-2"></i>Filter
                                    </button>
                                    <a href="{{ route('admin.affiliate.commission.index') }}"
                                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                                        <i class="fas fa-redo"></i>
                                    </a>
                                </div>
                            </form>
                        </div>

                        <!-- Data Table -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                                        <tr>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                Scope</th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                Type</th>
                                            <th
                                                class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                Percentage</th>
                                            <th
                                                class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                Fixed Amount</th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                Valid Period</th>
                                            <th
                                                class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                Status</th>
                                            <th
                                                class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse($settings as $setting)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-6 py-4">
                                                    @if(!$setting->marketing_id && !$setting->cluster_id)
                                                        <span
                                                            class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">
                                                            <i class="fas fa-globe mr-1"></i>Global
                                                        </span>
                                                    @else
                                                        <div class="text-sm">
                                                            @if($setting->marketing_id)
                                                                <div class="text-gray-800 font-medium">
                                                                    {{ $setting->marketing->name ?? 'N/A' }}</div>
                                                            @endif
                                                            @if($setting->cluster_id)
                                                                <div class="text-gray-500">{{ $setting->cluster->name ?? 'N/A' }}</div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4">
                                                    @if($setting->commission_type === 'Percentage')
                                                        <span
                                                            class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">Percentage</span>
                                                    @elseif($setting->commission_type === 'Fixed')
                                                        <span
                                                            class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Fixed</span>
                                                    @else
                                                        <span
                                                            class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-semibold">Both</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-right font-semibold text-gray-800">
                                                    {{ number_format($setting->commission_percentage, 1) }}%</td>
                                                <td class="px-6 py-4 text-right font-semibold text-gray-800">Rp
                                                    {{ number_format($setting->fixed_amount, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-600">
                                                    {{ $setting->valid_from ? \Carbon\Carbon::parse($setting->valid_from)->format('d M Y') : '-' }}
                                                    @if($setting->valid_until)
                                                        - {{ \Carbon\Carbon::parse($setting->valid_until)->format('d M Y') }}
                                                    @else
                                                        - <span class="text-green-600 font-medium">No Limit</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    @if($setting->is_active)
                                                        <span
                                                            class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Active</span>
                                                    @else
                                                        <span
                                                            class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">Inactive</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <div class="flex items-center justify-center gap-2">
                                                        <a href="{{ route('admin.affiliate.commission.edit', $setting) }}"
                                                            class="px-3 py-1.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-sm">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form
                                                            action="{{ route('admin.affiliate.commission.destroy', $setting) }}"
                                                            method="POST" class="inline"
                                                            onsubmit="return confirm('Yakin ingin menghapus setting ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="px-3 py-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="px-6 py-12 text-center">
                                                    <i class="fas fa-inbox text-gray-300 text-5xl mb-3"></i>
                                                    <p class="text-gray-400 font-medium">Belum ada commission settings</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if($settings->hasPages())
                                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                                    {{ $settings->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </body>
@endsection