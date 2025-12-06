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
                    <!-- Welcome Banner - Hidden -->
                    <div
                        class="hidden bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg p-4 lg:p-6 text-white">
                        <div class="flex items-center justify-between flex-wrap gap-3">
                            <div class="flex-1">
                                <h1 class="text-lg lg:text-xl font-bold mb-1">
                                    <i class="fa-solid fa-hand-sparkles mr-1.5"></i>
                                    Selamat Datang, {{ Auth::user()->name }}!
                                </h1>
                                <p class="text-blue-100 text-xs lg:text-sm">
                                    Kelola cluster dan merchant iHome Anda dengan mudah dari satu dashboard terpusat
                                </p>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <!-- Real-time Clock -->
                                <div
                                    class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg px-4 py-2 border border-white border-opacity-20">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-clock text-xl"></i>
                                        <div>
                                            <div id="live-clock" class="text-2xl font-bold font-mono tracking-wide">--:--:--
                                            </div>
                                            <div id="live-date" class="text-xs opacity-90 text-center">-</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="hidden lg:block opacity-20">
                                    <i class="fa-solid fa-chart-line text-4xl"></i>
                                </div>
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
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Active
                                        Clusters
                                    </p>
                                    <p class="text-2xl lg:text-3xl font-bold text-gray-800">{{ $active_clusters }}</p>
                                    <p class="text-xs text-green-600 mt-1.5">
                                        <i class="fa-solid fa-arrow-up mr-1"></i> Total cluster terdaftar
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
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Active
                                        Subscriptions</p>
                                    <p class="text-2xl lg:text-3xl font-bold text-gray-800">
                                        {{ $pending_subscriptions }}
                                    </p>
                                    <p class="text-xs text-blue-600 mt-1.5">
                                        <i class="fa-solid fa-certificate mr-1"></i> Langganan aktif
                                    </p>
                                </div>
                                <div
                                    class="w-14 h-14 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center shadow-lg">
                                    <i class="fa-solid fa-certificate text-xl text-white"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Total Revenue Card -->
                        <div
                            class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-4 lg:p-5 border-t-4 border-green-600">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Total
                                        Revenue</p>
                                    <p class="text-xl lg:text-2xl font-bold text-gray-800">
                                        Rp {{ number_format($total_revenue, 0, ',', '.') }}
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
                                <a href="{{ route('admin.clusters.create') }}"
                                    class="w-full text-left px-3 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg flex items-center gap-2.5 hover:shadow-lg transition-all">
                                    <div class="w-9 h-9 rounded-lg bg-white bg-opacity-20 flex items-center justify-center">
                                        <i class="fa-solid fa-plus text-sm"></i>
                                    </div>
                                    <span class="font-medium text-sm">Create New Cluster</span>
                                </a>
                                <a href="{{ route('admin.users.create') }}"
                                    class="w-full text-left px-3 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg flex items-center gap-2.5 transition-all border border-gray-200">
                                    <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <i class="fa-solid fa-user-plus text-blue-600 text-sm"></i>
                                    </div>
                                    <span class="font-medium text-sm">Invite Admin</span>
                                </a>
                                <a href="{{ route('admin.finance.subscription.index') }}"
                                    class="w-full text-left px-3 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg flex items-center gap-2.5 transition-all border border-gray-200">
                                    <div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center">
                                        <i class="fa-solid fa-file-invoice-dollar text-green-600 text-sm"></i>
                                    </div>
                                    <span class="font-medium text-sm">Billing Overview</span>
                                </a>
                                <a href="{{ route('admin.affiliate.revenue.index') }}"
                                    class="w-full text-left px-3 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg flex items-center gap-2.5 transition-all border border-gray-200">
                                    <div class="w-9 h-9 rounded-lg bg-purple-100 flex items-center justify-center">
                                        <i class="fa-solid fa-chart-pie text-purple-600 text-sm"></i>
                                    </div>
                                    <span class="font-medium text-sm">View Reports</span>
                                </a>
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
                                @forelse ($recent_clusters as $cluster)
                                    @php
                                        $createdTime = strtotime($cluster->created_at);
                                        $currentTime = time();
                                        $timeDiff = $currentTime - $createdTime;
                                        
                                        $seconds = $timeDiff;
                                        $minutes = floor($seconds / 60);
                                        $hours = floor($minutes / 60);
                                        $days = floor($hours / 24);
                                        $weeks = floor($days / 7);
                                        $months = floor($days / 30);
                                        $years = floor($days / 365);
                                        
                                        if ($seconds < 60) {
                                            $timeAgo = 'Baru saja';
                                        } elseif ($minutes < 60) {
                                            $timeAgo = $minutes . ' menit yang lalu';
                                        } elseif ($hours < 24) {
                                            $timeAgo = $hours . ' jam yang lalu';
                                        } elseif ($days == 1) {
                                            $timeAgo = 'Kemarin';
                                        } elseif ($days < 7) {
                                            $timeAgo = $days . ' hari yang lalu';
                                        } elseif ($weeks < 4) {
                                            $timeAgo = $weeks . ' minggu yang lalu';
                                        } elseif ($months < 12) {
                                            $timeAgo = $months . ' bulan yang lalu';
                                        } else {
                                            $timeAgo = $years . ' tahun yang lalu';
                                        }
                                    @endphp
                                    <div
                                        class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                        <div class="flex items-center gap-2.5">
                                            @if ($cluster->logo && file_exists(storage_path('app/public/' . $cluster->logo)))
                                                <img src="{{ asset('storage/' . $cluster->logo) }}" 
                                                    alt="{{ $cluster->name }}"
                                                    class="w-9 h-9 rounded-full object-cover">
                                            @else
                                                <div
                                                    class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm">
                                                    {{ strtoupper(substr($cluster->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-medium text-gray-800 text-sm">{{ $cluster->name }}</p>
                                                <p class="text-xs">
                                                    <span class="text-gray-500">Terdaftar</span>
                                                    <span class="font-semibold text-blue-600">{{ $timeAgo }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <span
                                            class="px-2.5 py-1 {{ $cluster->active_flag ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-semibold">{{ $cluster->active_flag ? 'Active' : 'Inactive' }}</span>
                                    </div>
                                @empty
                                    <div class="text-center py-8 text-gray-500">
                                        <i class="fa-solid fa-inbox text-4xl mb-2"></i>
                                        <p class="text-sm">Belum ada cluster terdaftar</p>
                                    </div>
                                @endforelse
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
                                        <span class="text-xs lg:text-sm font-medium text-gray-700">Database
                                            Performance</span>
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
        // Real-time Clock Function
        function updateClock() {
            const now = new Date();

            // Format time (HH:MM:SS)
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeString = `${hours}:${minutes}:${seconds}`;

            // Format date (Day, DD Month YYYY)
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Oct', 'Nov', 'Des'];
            const dayName = days[now.getDay()];
            const day = String(now.getDate()).padStart(2, '0');
            const month = months[now.getMonth()];
            const year = now.getFullYear();
            const dateString = `${dayName}, ${day} ${month} ${year}`;

            // Update DOM
            document.getElementById('live-clock').textContent = timeString;
            document.getElementById('live-date').textContent = dateString;
        }

        // Animate Counter Function
        function animateCounter(element, target, duration = 2000) {
            const start = 0;
            const increment = target / (duration / 16);
            let current = start;

            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target.toLocaleString('id-ID');
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current).toLocaleString('id-ID');
                }
            }, 16);
        }

        // Format currency for animation
        function animateCurrency(element, target, duration = 2000) {
            const start = 0;
            const increment = target / (duration / 16);
            let current = start;

            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = 'Rp ' + target.toLocaleString('id-ID');
                    clearInterval(timer);
                } else {
                    element.textContent = 'Rp ' + Math.floor(current).toLocaleString('id-ID');
                }
            }, 16);
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Start clock immediately and update every second
            updateClock();
            setInterval(updateClock, 1000);

            // Animate statistics cards on page load
            const cards = document.querySelectorAll('.grid.grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-3 > div');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease-out';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 50);
                }, index * 150);
            });

            // Animate progress bars
            setTimeout(() => {
                const progressBars = document.querySelectorAll('.bg-green-600, .bg-blue-600, .bg-yellow-600');
                progressBars.forEach((bar, index) => {
                    setTimeout(() => {
                        const width = bar.style.width;
                        bar.style.width = '0%';
                        bar.style.transition = 'width 1.5s ease-out';
                        setTimeout(() => {
                            bar.style.width = width;
                        }, 50);
                    }, index * 200);
                });
            }, 1000);

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
@endpush