@extends('layouts.app')

@section('title', 'Rekap Revenue Marketing')

@section('content')
    <div class="flex min-h-screen bg-gradient-to-br from-gray-50 to-blue-50" x-data="{ sidebarOpen: false }">
        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <!-- Sidebar Component -->
        <x-admin.sidebar activeMenu="affiliate.revenue" />

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
                                <i class="fa-solid fa-chart-bar text-indigo-600"></i>
                                Rekap Revenue per Marketing
                            </h1>
                            <p class="text-sm text-gray-600 mt-1">Laporan revenue dan komisi untuk setiap sales marketing
                            </p>
                        </div>
                    </div>

                    <!-- Filter -->
                    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border border-gray-100">
                        <form method="GET">
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                <div class="md:col-span-1">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Marketing</label>
                                    <select name="marketing_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                        <option value="">Semua Marketing</option>
                                        @foreach($marketings as $m)
                                            <option value="{{ $m->id }}" {{ $marketingId == $m->id ? 'selected' : '' }}>
                                                {{ $m->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Bulan</label>
                                    <select name="month"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun</label>
                                    <select name="year"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                        @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)
                                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status Bayar</label>
                                    <select name="payment_status"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                        <option value="">Semua</option>
                                        <option value="Paid" {{ $paymentStatus === 'Paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="Pending" {{ $paymentStatus === 'Pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="Cancelled" {{ $paymentStatus === 'Cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                    </select>
                                </div>
                                <div class="flex items-end gap-2">
                                    <button type="submit"
                                        class="flex-1 px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                        <i class="fa-solid fa-filter mr-2"></i>Filter
                                    </button>
                                    <a href="{{ route('admin.affiliate.revenue.index') }}"
                                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                                        <i class="fa-solid fa-redo"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Statistics -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">
                        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-blue-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Total Revenue</p>
                                    <p class="text-lg lg:text-xl font-bold text-gray-800 mt-1">Rp
                                        {{ number_format($totalRevenue / 1000000, 1) }}M</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="fa-solid fa-money-bill-wave text-blue-600"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-green-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Total Komisi</p>
                                    <p class="text-lg lg:text-xl font-bold text-green-600 mt-1">Rp
                                        {{ number_format($totalCommission / 1000000, 1) }}M</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="fa-solid fa-hand-holding-usd text-green-600"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-indigo-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Komisi Dibayar</p>
                                    <p class="text-lg lg:text-xl font-bold text-indigo-600 mt-1">Rp
                                        {{ number_format($paidCommission / 1000000, 1) }}M</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <i class="fa-solid fa-check-circle text-indigo-600"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-amber-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Komisi Pending</p>
                                    <p class="text-lg lg:text-xl font-bold text-amber-600 mt-1">Rp
                                        {{ number_format($pendingCommission / 1000000, 1) }}M</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                                    <i class="fa-solid fa-clock text-amber-600"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart -->
                    @if(!empty($chartLabels))
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                                <h6 class="font-bold text-indigo-700 flex items-center gap-2">
                                    <i class="fa-solid fa-chart-line"></i>
                                    Grafik Revenue & Komisi per Marketing
                                </h6>
                            </div>
                            <div class="p-6">
                                <canvas id="revenueChart" class="w-full" style="max-height: 400px;"></canvas>
                            </div>
                        </div>
                    @endif

                    <!-- Marketing Stats Table -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                            <h6 class="font-bold text-indigo-700 flex items-center gap-2">
                                <i class="fa-solid fa-table"></i>
                                Rekap per Marketing
                            </h6>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                                    <tr>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Marketing</th>
                                        <th
                                            class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Total Cluster</th>
                                        <th
                                            class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Total Revenue</th>
                                        <th
                                            class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Total Komisi</th>
                                        <th
                                            class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Komisi Dibayar</th>
                                        <th
                                            class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Komisi Pending</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($marketingStats as $stat)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                        {{ strtoupper(substr($stat['marketing']->name, 0, 2)) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-semibold text-gray-800">{{ $stat['marketing']->name }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            {{ $stat['marketing']->referral_code }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span
                                                    class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">{{ $stat['total_clusters'] }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-right font-bold text-gray-800">Rp
                                                {{ number_format($stat['total_revenue'], 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 text-right font-bold text-indigo-600">Rp
                                                {{ number_format($stat['total_commission'], 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 text-right text-green-600">Rp
                                                {{ number_format($stat['paid_commission'], 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 text-right text-amber-600">Rp
                                                {{ number_format($stat['pending_commission'], 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-12 text-center">
                                                <i class="fa-solid fa-inbox text-gray-300 text-5xl mb-3"></i>
                                                <p class="text-gray-400 font-medium">Tidak ada data revenue untuk periode ini
                                                </p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    @if(!empty($chartLabels))
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [
                        {
                            label: 'Revenue',
                            data: {!! json_encode($chartRevenues) !!},
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Komisi',
                            data: {!! json_encode($chartCommissions) !!},
                            backgroundColor: 'rgba(249, 115, 22, 0.8)',
                            borderColor: 'rgba(249, 115, 22, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        </script>
    @endif
@endsection