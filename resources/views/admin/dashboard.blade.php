@extends('layouts.app')

@section('title', 'Dashboard - iManagement')

@section('content')
    <div class="flex min-h-screen bg-gradient-to-br from-gray-50 to-blue-50" x-data="{ sidebarOpen: false }">
        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <!-- Sidebar Component -->
        <x-admin.sidebar activeMenu="dashboard" />

        <!-- Main Wrapper -->
        <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">
            <!-- Header Component -->
            <x-admin.header />

            <!-- Main Content - Scrollable -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <div class="w-full space-y-4 lg:space-y-6">
                <!-- Welcome Banner -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg p-4 lg:p-6 text-white">
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        <div>
                            <h1 class="text-lg lg:text-xl font-bold mb-1">
                                <i class="fa-solid fa-hand-sparkles mr-1.5"></i>
                                Selamat Datang, {{ Auth::user()->name }}!
                            </h1>
                            <p class="text-blue-100 text-xs lg:text-sm">
                                Kelola cluster dan merchant iHome Anda dengan mudah dari satu dashboard terpusat
                            </p>
                        </div>
                        <div class="hidden lg:block">
                            <i class="fa-solid fa-chart-line text-5xl opacity-20"></i>
                        </div>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Active Clusters Card -->
                    <div
                        class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-4 lg:p-5 border-t-4 border-blue-600">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Active Clusters
                                </p>
                                <p class="text-2xl lg:text-3xl font-bold text-gray-800">{{ $active_clusters ?? 128 }}</p>
                                <p class="text-xs text-green-600 mt-1.5">
                                    <i class="fa-solid fa-arrow-up mr-1"></i> +12% dari bulan lalu
                                </p>
                            </div>
                            <div
                                class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-lg">
                                <i class="fa-solid fa-network-wired text-xl text-white"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Subscriptions Card -->
                    <div
                        class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-4 lg:p-5 border-t-4 border-yellow-500">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Pending
                                    Subscriptions</p>
                                <p class="text-2xl lg:text-3xl font-bold text-gray-800">{{ $pending_subscriptions ?? 12 }}
                                </p>
                                <p class="text-xs text-yellow-600 mt-1.5">
                                    <i class="fa-solid fa-clock mr-1"></i> Perlu review
                                </p>
                            </div>
                            <div
                                class="w-14 h-14 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center shadow-lg">
                                <i class="fa-solid fa-hourglass-half text-xl text-white"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Total Revenue Card -->
                    <div
                        class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-4 lg:p-5 border-t-4 border-green-600">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Total Revenue</p>
                                <p class="text-xl lg:text-2xl font-bold text-gray-800">
                                    {{ isset($total_revenue) ? 'Rp ' . number_format($total_revenue, 0, ',', '.') : 'Rp ' . number_format(125000000, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-green-600 mt-1.5">
                                    <i class="fa-solid fa-arrow-trend-up mr-1"></i> Bulan ini
                                </p>
                            </div>
                            <div
                                class="w-14 h-14 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg">
                                <i class="fa-solid fa-coins text-xl text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity & Quick Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <!-- User Activity Chart -->
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-4 lg:p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base lg:text-lg font-bold text-gray-800 flex items-center gap-2">
                                <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <i class="fa-solid fa-chart-column text-blue-600 text-sm"></i>
                                </div>
                                <span>Aktivitas Pengguna (7 Hari Terakhir)</span>
                            </h3>
                            <button class="text-xs text-blue-600 hover:text-blue-800 transition">
                                <i class="fa-solid fa-expand mr-1"></i> Full View
                            </button>
                        </div>
                        <div id="user-activity-chart" class="w-full h-72"></div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow-lg p-4 lg:p-5">
                        <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <div class="w-9 h-9 rounded-lg bg-yellow-100 flex items-center justify-center">
                                <i class="fa-solid fa-bolt text-yellow-600 text-sm"></i>
                            </div>
                            <span>Quick Actions</span>
                        </h3>
                        <div class="space-y-2.5">
                            <button
                                class="w-full text-left px-3 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg flex items-center gap-2.5 hover:shadow-lg transition-all">
                                <div class="w-9 h-9 rounded-lg bg-white bg-opacity-20 flex items-center justify-center">
                                    <i class="fa-solid fa-plus text-sm"></i>
                                </div>
                                <span class="font-medium text-sm">Create New Cluster</span>
                            </button>
                            <button
                                class="w-full text-left px-3 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg flex items-center gap-2.5 transition-all border border-gray-200">
                                <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <i class="fa-solid fa-user-plus text-blue-600 text-sm"></i>
                                </div>
                                <span class="font-medium text-sm">Invite Admin</span>
                            </button>
                            <button
                                class="w-full text-left px-3 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg flex items-center gap-2.5 transition-all border border-gray-200">
                                <div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center">
                                    <i class="fa-solid fa-file-invoice-dollar text-green-600 text-sm"></i>
                                </div>
                                <span class="font-medium text-sm">Billing Overview</span>
                            </button>
                            <button
                                class="w-full text-left px-3 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg flex items-center gap-2.5 transition-all border border-gray-200">
                                <div class="w-9 h-9 rounded-lg bg-purple-100 flex items-center justify-center">
                                    <i class="fa-solid fa-chart-pie text-purple-600 text-sm"></i>
                                </div>
                                <span class="font-medium">View Reports</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity & Statistics -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <!-- Recent Clusters -->
                    <div class="bg-white rounded-xl shadow-lg p-4 lg:p-5">
                        <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left text-blue-600 text-sm"></i>
                            <span>Cluster Terbaru</span>
                        </h3>
                        <div class="space-y-2.5">
                            @for ($i = 1; $i <= 5; $i++)
                                <div
                                    class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <div class="flex items-center gap-2.5">
                                        <div
                                            class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm">
                                            {{ chr(64 + $i) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800 text-sm">Cluster {{ chr(64 + $i) }}</p>
                                            <p class="text-xs text-gray-500">Registered {{ $i }} days ago</p>
                                        </div>
                                    </div>
                                    <span
                                        class="px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Active</span>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- System Status -->
                    <div class="bg-white rounded-xl shadow-lg p-4 lg:p-5">
                        <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-server text-green-600 text-sm"></i>
                            <span>System Status</span>
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-xs lg:text-sm font-medium text-gray-700">Server Uptime</span>
                                    <span class="text-xs lg:text-sm font-bold text-green-600">99.9%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: 99.9%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-xs lg:text-sm font-medium text-gray-700">Database Performance</span>
                                    <span class="text-xs lg:text-sm font-bold text-blue-600">95.2%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 95.2%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-xs lg:text-sm font-medium text-gray-700">API Response Time</span>
                                    <span class="text-xs lg:text-sm font-bold text-yellow-600">87.5%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-600 h-2 rounded-full" style="width: 87.5%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </main>

            <!-- Footer Component -->
            <x-admin.footer />
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
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
                    options3d: {
                        enabled: true,
                        alpha: 15,
                        beta: 15,
                        depth: 50,
                        viewDistance: 25
                    },
                    style: {
                        fontFamily: 'Poppins, sans-serif'
                    }
                },
                title: {
                    text: null
                },
                xAxis: {
                    categories: labels,
                    crosshair: {
                        color: '#E5E7EB',
                        width: 1
                    },
                    labels: {
                        style: {
                            fontSize: '12px',
                            color: '#6B7280',
                            fontWeight: '500'
                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Aktivitas User',
                        style: {
                            fontSize: '12px',
                            color: '#6B7280',
                            fontWeight: '600'
                        }
                    },
                    gridLineColor: '#E5E7EB',
                    gridLineDashStyle: 'Dash'
                },
                tooltip: {
                    headerFormat: '<div style="font-size:13px; font-weight:bold; margin-bottom:5px">{point.key}</div>',
                    pointFormat: '<div style="display:flex; align-items:center; gap:8px">' +
                        '<span style="color:{series.color}; font-size:20px">\u25CF</span>' +
                        '<span style="font-weight:600">{series.name}:</span> ' +
                        '<span style="font-weight:bold; color:{series.color}">{point.y}</span>' +
                        '</div>',
                    shared: true,
                    useHTML: true,
                    backgroundColor: 'white',
                    borderColor: '#E5E7EB',
                    borderRadius: 12,
                    shadow: {
                        color: 'rgba(0, 0, 0, 0.1)',
                        offsetX: 0,
                        offsetY: 4,
                        opacity: 0.5,
                        width: 8
                    },
                    style: {
                        padding: '12px'
                    }
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.15,
                        borderWidth: 0,
                        borderRadius: 6,
                        depth: 25,
                        dataLabels: {
                            enabled: false
                        },
                        cursor: 'pointer',
                        events: {
                            click: function (e) {
                                alert('Aktivitas pada ' + e.point.category + ': ' + e.point.y + ' user');
                            }
                        },
                        states: {
                            hover: {
                                brightness: 0.1,
                                borderColor: '#1E40AF',
                                borderWidth: 2
                            }
                        }
                    }
                },
                series: [{
                    name: 'Aktivitas User',
                    data: activityData,
                    color: {
                        linearGradient: {
                            x1: 0,
                            x2: 0,
                            y1: 0,
                            y2: 1
                        },
                        stops: [
                            [0, '#2563EB'],
                            [0.5, '#3B82F6'],
                            [1, '#60A5FA']
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
                            chart: {
                                options3d: {
                                    enabled: false
                                }
                            },
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
                </div>
            </main>

            <!-- Footer Component -->
            <x-admin.footer />
        </div>
    </div>
@endpush