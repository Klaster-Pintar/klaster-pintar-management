@extends('layouts.app')

@section('title', 'Dashboard - iManagement')

@section('content')
    <div class="flex min-h-screen bg-gray-100" x-data="{ sidebarOpen: false }">
        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <!-- Sidebar - Fixed -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="w-64 bg-white shadow-lg fixed inset-y-0 left-0 lg:translate-x-0 transition-transform duration-300 z-50">
            <div class="h-full flex flex-col">
                <!-- Logo - Clickable -->
                <div class="flex items-center gap-2 p-4 border-b border-gray-200">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 w-full">
                        <img src="{{ asset('images/logo.png') }}" class="w-full" alt="Logo" />
                    </a>
                    <!-- Close button for mobile -->
                    <button @click="sidebarOpen = false" class="lg:hidden text-gray-600 text-2xl ml-auto">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>

                <!-- Navigation - Scrollable -->
                <nav class="flex-1 overflow-y-auto p-4 space-y-2 text-gray-700">
                    <a class="flex items-center gap-3 p-2 rounded-lg bg-blue-100 text-blue-700"
                        href="{{ route('admin.dashboard') }}">
                        <i class="fa-solid fa-house"></i>
                        <span>Dashboard</span>
                    </a>

                    <div x-data="{ open: false }" class="mt-2">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between gap-3 p-2 rounded-lg hover:bg-gray-100">
                            <span class="flex items-center gap-3">
                                <i class="fa-solid fa-layer-group"></i>
                                <span>Master</span>
                            </span>
                            <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" class="fa-solid"></i>
                        </button>

                        <div x-show="open" x-collapse class="mt-2 space-y-1 pl-8">
                            <a href="#" class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100">
                                <i class="fa-solid fa-user"></i> User
                            </a>
                            <a href="#" class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100">
                                <i class="fa-solid fa-warehouse"></i> Cluster
                            </a>
                        </div>
                    </div>

                    <div x-data="{ open: false }" class="mt-1">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between gap-3 p-2 rounded-lg hover:bg-gray-100">
                            <span class="flex items-center gap-3">
                                <i class="fa-solid fa-wallet"></i>
                                <span>Finance</span>
                            </span>
                            <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" class="fa-solid"></i>
                        </button>

                        <div x-show="open" x-collapse class="mt-2 space-y-1 pl-8">
                            <a href="#" class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100">
                                <i class="fa-solid fa-receipt"></i> Cluster Subscription
                            </a>
                            <a href="#" class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100">
                                <i class="fa-solid fa-bell"></i> Notification
                            </a>
                            <a href="#" class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100">
                                <i class="fa-solid fa-scale-balanced"></i> Reconsiliation
                            </a>
                            <a href="#" class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100">
                                <i class="fa-solid fa-file-invoice-dollar"></i> Finance Reports
                            </a>
                        </div>
                    </div>

                    <div x-data="{ open: false }" class="mt-1">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between gap-3 p-2 rounded-lg hover:bg-gray-100">
                            <span class="flex items-center gap-3">
                                <i class="fa-solid fa-microchip"></i>
                                <span>IoT Management</span>
                            </span>
                            <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" class="fa-solid"></i>
                        </button>

                        <div x-show="open" x-collapse class="mt-2 space-y-1 pl-8">
                            <a href="#" class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100">
                                <i class="fa-solid fa-satellite-dish"></i> Device Management
                            </a>
                            <a href="#" class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100">
                                <i class="fa-solid fa-file-lines"></i> Log & Monitoring Data
                            </a>
                        </div>
                    </div>

                    <div x-data="{ open: false }" class="mt-1">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between gap-3 p-2 rounded-lg hover:bg-gray-100">
                            <span class="flex items-center gap-3">
                                <i class="fa-solid fa-handshake"></i>
                                <span>Affiliate</span>
                            </span>
                            <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" class="fa-solid"></i>
                        </button>

                        <div x-show="open" x-collapse class="mt-2 space-y-1 pl-8">
                            <a href="#" class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100">
                                <i class="fa-solid fa-users"></i> Affiliate Users
                            </a>
                            <a href="#" class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100">
                                <i class="fa-solid fa-chart-simple"></i> Affiliate Reports
                            </a>
                            <a href="#" class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100">
                                <i class="fa-solid fa-coins"></i> Commissions Mechainism
                            </a>
                            <a href="#" class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100">
                                <i class="fa-solid fa-file-contract"></i> Lead Tracking Reports
                            </a>
                        </div>
                    </div>
                </nav>
        </aside>

        <!-- Main Wrapper - dengan padding untuk sidebar fixed -->
        <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">
            <!-- Header - Fixed -->
            <header class="bg-white shadow-sm sticky top-0 z-30">
                <div class="flex justify-between items-center p-4 lg:px-8">
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
                        <i
                            class="fa-solid fa-globe text-lg lg:text-xl text-gray-600 cursor-pointer hover:text-blue-600"></i>
                        <div class="relative">
                            <i class="fa-solid fa-bell text-lg lg:text-xl text-gray-600 cursor-pointer hover:text-blue-600">
                                <span
                                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">3</span>
                            </i>
                        </div>
                        <a href="{{ route('admin.settings') }}"
                            class="fa-solid fa-gear text-lg lg:text-xl text-gray-600 cursor-pointer hover:text-blue-600"></a>

                        <!-- User Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <div @click="open = !open"
                                class="hidden lg:flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-full cursor-pointer hover:shadow-lg transition">
                                @if (Auth::user()->avatar && file_exists(storage_path('app/public/' . Auth::user()->avatar)))
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar"
                                        class="w-8 h-8 rounded-full object-cover border-2 border-white">
                                @else
                                    <i class="fa-solid fa-user-circle text-xl"></i>
                                @endif
                                <span class="font-medium">{{ Auth::user()->name }}</span>
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-100"
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
                                <a href="{{ route('admin.settings') }}"
                                    class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
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
            </header>

                <!-- Main Content - Scrollable -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6 space-y-6">
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
                        <div class="w-14 h-14 rounded-lg bg-linear-to-r from-blue-600 to-indigo-600 flex items-center justify-center text-white">
                            <i class="fa-solid fa-network-wired fa-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Active Clusters</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $active_clusters ?? 128 }}</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
                        <div class="w-14 h-14 rounded-lg bg-linear-to-r from-yellow-500 to-orange-500 flex items-center justify-center text-white">
                            <i class="fa-solid fa-hourglass-half fa-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Pending Subscriptions</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $pending_subscriptions ?? 12 }}</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
                        <div class="w-14 h-14 rounded-lg bg-linear-to-r from-green-500 to-emerald-600 flex items-center justify-center text-white">
                            <i class="fa-solid fa-coins fa-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Revenue</p>
                            <p class="text-2xl font-bold text-gray-800">{{ isset($total_revenue) ? 'Rp ' . number_format($total_revenue,0,',','.') : 'Rp ' . number_format(125000000,0,',','.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Activity & Charts -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
                        <h3 class="font-semibold text-lg mb-4 flex items-center gap-3">
                            <i class="fa-solid fa-chart-column text-blue-600"></i>
                            Aktivitas Pengguna (7 Hari Terakhir)
                        </h3>
                        <div id="user-activity-chart" class="w-full h-64"></div>
                    </div>

                    <div class="bg-white rounded-xl shadow p-6">
                        <h3 class="font-semibold text-lg mb-4 flex items-center gap-3">
                            <i class="fa-solid fa-bell text-yellow-600"></i>
                            Quick Actions
                        </h3>
                        <div class="space-y-3">
                            <button class="w-full text-left px-4 py-2 bg-blue-50 text-blue-700 rounded-lg flex items-center gap-3">
                                <i class="fa-solid fa-plus"></i> Create Cluster
                            </button>
                            <button class="w-full text-left px-4 py-2 bg-gray-50 text-gray-700 rounded-lg flex items-center gap-3">
                                <i class="fa-solid fa-user-plus"></i> Invite Admin
                            </button>
                            <button class="w-full text-left px-4 py-2 bg-gray-50 text-gray-700 rounded-lg flex items-center gap-3">
                                <i class="fa-solid fa-file-invoice-dollar"></i> Billing Overview
                            </button>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Prepare labels for last 7 days
            const today = new Date();
            const labels = [];
            const dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
            
            for (let i = 6; i >= 0; i--) {
                const date = new Date(today);
                date.setDate(date.getDate() - i);
                labels.push(dayNames[date.getDay()]);
            }

            // Dummy data - replace with real data from backend
            const activityData = [12, 24, 18, 30, 22, 16, 28];

            Highcharts.chart('user-activity-chart', {
                chart: {
                    type: 'column',
                    backgroundColor: 'transparent',
                    style: {
                        fontFamily: 'Poppins, sans-serif'
                    }
                },
                title: {
                    text: null
                },
                xAxis: {
                    categories: labels,
                    crosshair: true,
                    labels: {
                        style: {
                            fontSize: '12px',
                            color: '#6B7280'
                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Aktivitas',
                        style: {
                            fontSize: '12px',
                            color: '#6B7280'
                        }
                    },
                    gridLineColor: '#E5E7EB'
                },
                tooltip: {
                    headerFormat: '<span style="font-size:12px"><b>{point.key}</b></span><br/>',
                    pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: <b>{point.y}</b><br/>',
                    shared: true,
                    backgroundColor: 'white',
                    borderColor: '#E5E7EB',
                    borderRadius: 8,
                    shadow: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0,
                        borderRadius: 6,
                        dataLabels: {
                            enabled: false
                        }
                    }
                },
                series: [{
                    name: 'Aktivitas User',
                    data: activityData,
                    color: {
                        linearGradient: { x1: 0, x2: 0, y1: 0, y2: 1 },
                        stops: [
                            [0, '#2563EB'],
                            [1, '#3B82F6']
                        ]
                    }
                }],
                legend: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                enabled: false
                            },
                            yAxis: {
                                title: {
                                    text: null
                                }
                            }
                        }
                    }]
                }
            });
        });
    </script>
@endpush