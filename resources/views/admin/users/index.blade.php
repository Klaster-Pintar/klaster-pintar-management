@extends('layouts.app')

@section('title', 'Owner Management - iManagement')

@section('content')
    <div class="flex min-h-screen bg-gradient-to-br from-gray-50 to-blue-50" x-data="{ sidebarOpen: false }">
        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <!-- Sidebar Component -->
        <x-admin.sidebar activeMenu="master.user" />

        <!-- Main Wrapper -->
        <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">
            <!-- Header Component -->
            <x-admin.header />

            <!-- Main Content - Scrollable -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <div class="w-full space-y-4">
                    <!-- Page Header -->
                    <div class="bg-white rounded-xl shadow-lg p-4 lg:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-user-shield text-purple-600 text-lg lg:text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl lg:text-2xl font-bold text-gray-800">Owner Management</h2>
                                    <p class="text-gray-600 text-xs lg:text-sm mt-0.5">Kelola akun owner sistem iManagement
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('admin.users.create') }}"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm rounded-lg hover:shadow-lg transition-all font-semibold">
                                <i class="fa-solid fa-user-plus"></i>
                                <span>Tambah Owner</span>
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
                        <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <!-- Search -->
                                <div class="sm:col-span-2">
                                    <label class="block text-xs lg:text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fa-solid fa-magnifying-glass mr-1 text-blue-600"></i> Cari Owner
                                    </label>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Nama, email, username, atau phone..."
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                </div>

                                <!-- Filter Status -->
                                <div>
                                    <label class="block text-xs lg:text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fa-solid fa-filter mr-1 text-blue-600"></i> Status
                                    </label>
                                    <select name="status"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                        <option value="">Semua Status</option>
                                        <option value="VERIFIED" {{ request('status') == 'VERIFIED' ? 'selected' : '' }}>
                                            Verified</option>
                                        <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="INACTIVE" {{ request('status') == 'INACTIVE' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition flex items-center gap-2 font-semibold">
                                    <i class="fa-solid fa-filter"></i>
                                    <span>Filter</span>
                                </button>
                                <a href="{{ route('admin.users.index') }}"
                                    class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition flex items-center gap-2 font-semibold">
                                    <i class="fa-solid fa-rotate-right"></i>
                                    <span>Reset</span>
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Users Table Card -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-4 lg:p-6">
                            <h3 class="text-lg lg:text-xl font-bold text-white flex items-center gap-2">
                                <i class="fa-solid fa-users-gear"></i>
                                <span>Daftar Owner</span>
                            </h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            User Info</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Contact</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Role</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Joined</th>
                                        <th
                                            class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($users as $user)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    @if ($user->avatar && file_exists(storage_path('app/public/' . $user->avatar)))
                                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                                                            class="w-12 h-12 rounded-full object-cover border-2 border-blue-500">
                                                    @else
                                                        <div
                                                            class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-lg font-bold border-2 border-white shadow">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                                        <p class="text-sm text-gray-500">@<span>{{ $user->username }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="space-y-1">
                                                    @if ($user->email)
                                                        <p class="text-sm text-gray-800 flex items-center gap-1">
                                                            <i class="fa-solid fa-envelope text-gray-400"></i>
                                                            {{ $user->email }}
                                                        </p>
                                                    @endif
                                                    @if ($user->phone)
                                                        <p class="text-sm text-gray-800 flex items-center gap-1">
                                                            <i class="fa-solid fa-phone text-gray-400"></i>
                                                            {{ $user->phone }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold
                                                                            @if ($user->role == 'ADMIN') bg-purple-100 text-purple-700
                                                                            @elseif($user->role == 'USER') bg-blue-100 text-blue-700
                                                                            @else bg-gray-100 text-gray-700 @endif">
                                                    <i class="fa-solid fa-user-tag"></i>
                                                    {{ $user->role }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="space-y-1">
                                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold
                                                                                @if ($user->status == 'VERIFIED') bg-green-100 text-green-700
                                                                                @elseif($user->status == 'PENDING') bg-yellow-100 text-yellow-700
                                                                                @else bg-red-100 text-red-700 @endif">
                                                        @if ($user->status == 'VERIFIED')
                                                            <i class="fa-solid fa-circle-check"></i>
                                                        @elseif($user->status == 'PENDING')
                                                            <i class="fa-solid fa-clock"></i>
                                                        @else
                                                            <i class="fa-solid fa-circle-xmark"></i>
                                                        @endif
                                                        {{ $user->status }}
                                                    </span>
                                                    @if ($user->blocked)
                                                        <span
                                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                                            <i class="fa-solid fa-ban"></i> Blocked
                                                        </span>
                                                    @elseif($user->suspend)
                                                        <span
                                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
                                                            <i class="fa-solid fa-pause-circle"></i> Suspended
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <p class="text-sm text-gray-800">
                                                    {{ $user->created_at->format('d M Y') }}
                                                </p>
                                                <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}
                                                </p>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center justify-center gap-2">
                                                    <a href="{{ route('admin.users.edit', $user) }}"
                                                        class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition"
                                                        title="Edit">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </a>
                                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                        onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition"
                                                            title="Delete">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center gap-3">
                                                    <i class="fa-solid fa-users-slash text-6xl text-gray-300"></i>
                                                    <p class="text-gray-500 font-medium">Tidak ada data user</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if ($users->hasPages())
                            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                                {{ $users->links() }}
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