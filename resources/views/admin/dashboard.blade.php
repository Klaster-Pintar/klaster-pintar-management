@extends('layouts.app')

@section('title', 'Dashboard - iManagement')

@section('content')
    <div class="flex min-h-screen" x-data="{ sidebarOpen: false }">
        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="w-64 bg-white shadow-lg p-4 fixed lg:static inset-y-0 left-0 lg:translate-x-0 transition-transform duration-300 z-50 overflow-y-auto">
            <!-- Close button for mobile -->
            <button @click="sidebarOpen = false" class="lg:hidden absolute top-4 right-4 text-gray-600 text-2xl">
                <i class="fa-solid fa-times"></i>
            </button>

            <div class="flex items-center gap-2 mb-6 mt-2">
                <img src="{{ asset('images/logo.png') }}" class="w-full" alt="Logo" />
            </div>

            <nav class="space-y-2 text-gray-700">
                <a class="flex items-center gap-3 p-2 rounded-lg bg-blue-100 text-blue-700"
                    href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-gauge"></i> Dashboard
                </a>
                <a class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-200" href="#">
                    <i class="fa-solid fa-water"></i> Waduk
                </a>
                <a class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-200" href="#">
                    <i class="fa-solid fa-chart-line"></i> Dashboard Waduk
                </a>
                <a class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-200" href="#">
                    <i class="fa-solid fa-video"></i> Live Monitoring
                </a>

                <p class="mt-4 text-xs font-semibold text-gray-500 uppercase">
                    Laporan
                </p>
                <a class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-200" href="#">
                    <i class="fa-solid fa-user-check"></i> Harian Petugas
                </a>
                <a class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-200" href="#">
                    <i class="fa-solid fa-shoe-prints"></i> Patroli
                </a>
                <a class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-200" href="#">
                    <i class="fa-solid fa-fingerprint"></i> Absensi
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 lg:p-6 space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center bg-white p-4 rounded-xl shadow">
                <!-- Mobile Menu Button -->
                <button @click="sidebarOpen = true" class="lg:hidden text-2xl text-gray-700">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <h2 class="text-sm lg:text-lg font-semibold text-gray-700">
                    <i class="fa-regular fa-calendar mr-2"></i>
                    {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, DD-MM-YYYY') }} &nbsp;
                    <i class="fa-regular fa-clock mr-1"></i>{{ \Carbon\Carbon::now()->format('H:i') }}
                </h2>

                <div class="flex items-center gap-2 lg:gap-4">
                    <i class="fa-solid fa-globe text-lg lg:text-xl text-gray-600 cursor-pointer hover:text-blue-600"></i>
                    <div class="relative">
                        <i class="fa-solid fa-bell text-lg lg:text-xl text-gray-600 cursor-pointer hover:text-blue-600">
                            <span
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">3</span>
                        </i>
                    </div>
                    <i class="fa-solid fa-gear text-lg lg:text-xl text-gray-600 cursor-pointer hover:text-blue-600"></i>

                    <!-- User Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <div @click="open = !open"
                            class="hidden lg:flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-full cursor-pointer hover:shadow-lg transition">
                            <i class="fa-solid fa-user-circle text-xl"></i>
                            <span class="font-medium">{{ Auth::user()->name }}</span>
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50"
                            style="display: none;">
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i class="fa-solid fa-user mr-2"></i> Profile
                            </a>
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i class="fa-solid fa-gear mr-2"></i> Settings
                            </a>
                            <hr class="my-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                    <i class="fa-solid fa-right-from-bracket mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informasi Waduk -->
                <section class="bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="font-bold text-xl mb-4 text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-water text-blue-600"></i>
                        Informasi Waduk
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div
                            class="bg-gradient-to-br from-blue-500 to-blue-700 text-white p-5 rounded-xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm opacity-90">Total Waduk</p>
                                <i class="fa-solid fa-database text-2xl opacity-80"></i>
                            </div>
                            <p class="text-4xl font-bold">{{ $total_clusters }}</p>
                        </div>
                        <div
                            class="bg-gradient-to-br from-red-500 to-red-700 text-white p-5 rounded-xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm opacity-90">Perbaikan</p>
                                <i class="fa-solid fa-wrench text-2xl opacity-80"></i>
                            </div>
                            <p class="text-4xl font-bold">{{ $clusters_in_repair }}<span
                                    class="text-xl">/{{ $total_clusters }}</span></p>
                        </div>
                        <div
                            class="bg-gradient-to-br from-green-500 to-green-700 text-white p-5 rounded-xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm opacity-90">Wisatawan</p>
                                <i class="fa-solid fa-users text-2xl opacity-80"></i>
                            </div>
                            <p class="text-4xl font-bold">{{ number_format($total_visitors, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </section>

                <!-- Informasi Pekerja -->
                <section class="bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="font-bold text-xl mb-4 text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-user-tie text-blue-600"></i>
                        Informasi Pekerja
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div
                            class="bg-gradient-to-br from-blue-500 to-blue-700 text-white p-5 rounded-xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm opacity-90">Total Pekerja</p>
                                <i class="fa-solid fa-user-group text-2xl opacity-80"></i>
                            </div>
                            <p class="text-4xl font-bold">{{ number_format($total_workers, 0, ',', '.') }}</p>
                        </div>
                        <div
                            class="bg-gradient-to-br from-yellow-500 to-yellow-700 text-white p-5 rounded-xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm opacity-90">Total Patroli</p>
                                <i class="fa-solid fa-shield-halved text-2xl opacity-80"></i>
                            </div>
                            <p class="text-4xl font-bold">{{ $total_patrols }}</p>
                        </div>
                        <div
                            class="bg-gradient-to-br from-green-500 to-green-700 text-white p-5 rounded-xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm opacity-90">Laporan</p>
                                <i class="fa-solid fa-clipboard-list text-2xl opacity-80"></i>
                            </div>
                            <p class="text-4xl font-bold">{{ $total_reports }}</p>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Charts placeholder -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div
                    class="bg-white h-64 lg:h-80 rounded-xl shadow-lg flex flex-col items-center justify-center text-gray-400 p-6">
                    <i class="fa-solid fa-chart-line text-6xl mb-4 text-blue-300"></i>
                    <p class="text-lg font-semibold">Chart Wisatawan</p>
                    <p class="text-sm text-gray-400">Data visualisasi wisatawan</p>
                </div>
                <div
                    class="bg-white h-64 lg:h-80 rounded-xl shadow-lg flex flex-col items-center justify-center text-gray-400 p-6">
                    <i class="fa-solid fa-chart-bar text-6xl mb-4 text-yellow-300"></i>
                    <p class="text-lg font-semibold">Chart Patroli</p>
                    <p class="text-sm text-gray-400">Data aktivitas patroli</p>
                </div>
            </div>
        </main>
    </div>
@endsection