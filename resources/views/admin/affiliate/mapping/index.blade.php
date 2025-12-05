@extends('layouts.app')

@section('title', 'Marketing Cluster Mapping')

@section('content')
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <div class="flex min-h-screen" x-data="{ sidebarOpen: false }">
        <!-- Mobile Sidebar Backdrop -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <!-- Sidebar Component -->
        <x-admin.sidebar activeMenu="affiliate.mapping" />

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
                                <i class="fas fa-link text-pink-600"></i>
                                Marketing Cluster Mapping
                            </h1>
                            <p class="text-gray-500 mt-1">Kelola mapping marketing dengan cluster</p>
                        </div>
                        <a href="{{ route('admin.affiliate.mapping.create') }}"
                            class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-pink-600 to-rose-600 text-white rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Mapping
                        </a>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-4">
                                <div class="p-3 bg-blue-50 rounded-lg">
                                    <i class="fas fa-list text-blue-600 text-2xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-sm font-medium">Total Mapping</p>
                                    <h3 class="text-2xl font-bold text-gray-800">{{ number_format($totalMappings) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-4">
                                <div class="p-3 bg-green-50 rounded-lg">
                                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-sm font-medium">Active</p>
                                    <h3 class="text-2xl font-bold text-green-600">{{ number_format($activeMappings) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-4">
                                <div class="p-3 bg-indigo-50 rounded-lg">
                                    <i class="fas fa-flag-checkered text-indigo-600 text-2xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-sm font-medium">Completed</p>
                                    <h3 class="text-2xl font-bold text-indigo-600">{{ number_format($completedMappings) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-4">
                                <div class="p-3 bg-red-50 rounded-lg">
                                    <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-sm font-medium">Cancelled</p>
                                    <h3 class="text-2xl font-bold text-red-600">{{ number_format($cancelledMappings) }}</h3>
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
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Marketing</label>
                                <select name="marketing_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                                    <option value="">Semua Marketing</option>
                                    @foreach($marketings as $m)
                                        <option value="{{ $m->id }}" {{ request('marketing_id') == $m->id ? 'selected' : '' }}>
                                            {{ $m->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Cluster</label>
                                <select name="cluster_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                                    <option value="">Semua Cluster</option>
                                    @foreach($clusters as $c)
                                        <option value="{{ $c->id }}" {{ request('cluster_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                                    <option value="">Semua Status</option>
                                    <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="flex items-end gap-2">
                                <button type="submit" class="flex-1 px-6 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition">
                                    <i class="fas fa-filter mr-2"></i>Filter
                                </button>
                                <a href="{{ route('admin.affiliate.mapping.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
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
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Marketing</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Cluster</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Join Date</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Commission %</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Commission Amount</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($mappings as $mapping)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-rose-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                        {{ strtoupper(substr($mapping->marketing->name ?? 'N/A', 0, 2)) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-semibold text-gray-800">{{ $mapping->marketing->name ?? 'N/A' }}</div>
                                                        <div class="text-xs text-gray-500">{{ $mapping->marketing->referral_code ?? '-' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-gray-800">{{ $mapping->cluster->name ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-500">{{ $mapping->cluster->code ?? '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ $mapping->join_date ? \Carbon\Carbon::parse($mapping->join_date)->format('d M Y') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-right font-semibold text-gray-800">{{ number_format($mapping->commission_percentage, 1) }}%</td>
                                            <td class="px-6 py-4 text-right font-semibold text-gray-800">Rp {{ number_format($mapping->commission_amount, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 text-center">
                                                @if($mapping->status === 'Active')
                                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Active</span>
                                                @elseif($mapping->status === 'Completed')
                                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">Completed</span>
                                                @else
                                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Cancelled</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <a href="{{ route('admin.affiliate.mapping.edit', $mapping) }}" 
                                                       class="px-3 py-1.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.affiliate.mapping.destroy', $mapping) }}" 
                                                          method="POST" class="inline"
                                                          onsubmit="return confirm('Yakin ingin menghapus mapping ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="px-3 py-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm">
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
                                                <p class="text-gray-400 font-medium">Belum ada data mapping</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($mappings->hasPages())
                            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                                {{ $mappings->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
@endsection
