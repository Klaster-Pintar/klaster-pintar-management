@extends('layouts.app')

@section('title', 'Cluster Subscription - Finance Management')

@section('content')
    <div class="flex min-h-screen bg-gradient-to-br from-gray-50 to-blue-50" x-data="{ sidebarOpen: false }">
        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <!-- Sidebar Component -->
        <x-admin.sidebar activeMenu="finance.subscription" />

        <!-- Main Wrapper -->
        <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">
            <!-- Header Component -->
            <x-admin.header />

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <div class="w-full space-y-4">
                    <!-- Page Header -->
                    <div class="bg-white rounded-xl shadow-lg p-4 lg:p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-receipt text-white text-lg lg:text-xl"></i>
                                </div>
                                <div>
                                    <h1 class="text-xl lg:text-2xl font-bold text-gray-800">Cluster Subscription Management
                                    </h1>
                                    <p class="text-xs lg:text-sm text-gray-600 mt-0.5">Kelola langganan dan masa aktif
                                        setiap cluster</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div
                            class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-4 text-white transform hover:scale-105 transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm opacity-90 font-medium">Active Subscriptions</p>
                                    <h3 class="text-3xl font-bold mt-1">{{ $clusters->where('latestSubscription.active', true)->where('latestSubscription.expired_at', '>=', now())->count() }}</h3>
                                </div>
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <i class="fa-solid fa-check-circle text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-4 text-white transform hover:scale-105 transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm opacity-90 font-medium">Expired</p>
                                    <h3 class="text-3xl font-bold mt-1">{{ $clusters->where('latestSubscription.expired_at', '<', now())->count() }}</h3>
                                </div>
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <i class="fa-solid fa-exclamation-triangle text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-4 text-white transform hover:scale-105 transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm opacity-90 font-medium">Expiring Soon (7 days)</p>
                                    <h3 class="text-3xl font-bold mt-1">{{ $clusters->filter(function($c) { return $c->latestSubscription && $c->latestSubscription->daysRemaining() <= 7 && $c->latestSubscription->daysRemaining() > 0; })->count() }}</h3>
                                </div>
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <i class="fa-solid fa-clock text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl shadow-lg p-4 text-white transform hover:scale-105 transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm opacity-90 font-medium">No Subscription</p>
                                    <h3 class="text-3xl font-bold mt-1">{{ $clusters->where('latestSubscription', null)->count() }}</h3>
                                </div>
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <i class="fa-solid fa-ban text-2xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6">
                        <form method="GET" action="{{ route('admin.finance.subscription.index') }}"
                            class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                            <!-- Search -->
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fa-solid fa-search mr-1"></i> Cari Cluster
                                </label>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                    placeholder="Nama cluster...">
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fa-solid fa-filter mr-1"></i> Status
                                </label>
                                <select name="status"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                                    <option value="">Semua Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired
                                    </option>
                                    <option value="no-subscription"
                                        {{ request('status') == 'no-subscription' ? 'selected' : '' }}>No Subscription
                                    </option>
                                </select>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-end">
                                <button type="submit"
                                    class="w-full px-6 py-2.5 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:shadow-lg transition font-semibold">
                                    <i class="fa-solid fa-search mr-1"></i> Filter
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Clusters Table -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-purple-600 to-purple-700 text-white">
                                    <tr>
                                        <th class="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider">No
                                        </th>
                                        <th class="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider">
                                            Cluster Name</th>
                                        <th class="px-4 py-4 text-right text-xs font-semibold uppercase tracking-wider">
                                            Subscription Amount</th>
                                        <th class="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider">
                                            Expired At</th>
                                        <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                                            Status</th>
                                        <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($clusters as $index => $cluster)
                                        @php
                                            $subscription = $cluster->latestSubscription;
                                            $isExpired = $subscription ? $subscription->isExpired() : true;
                                            $daysRemaining = $subscription ? $subscription->daysRemaining() : 0;
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-4 py-4 text-sm text-gray-700">
                                                {{ $clusters->firstItem() + $index }}
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center">
                                                        <i class="fa-solid fa-building text-purple-600"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-semibold text-gray-900">
                                                            {{ $cluster->name }}</div>
                                                        <div class="text-xs text-gray-500">{{ $cluster->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-right">
                                                @if ($subscription)
                                                    <span class="text-sm font-bold text-purple-700">
                                                        Rp {{ number_format($subscription->price, 0, ',', '.') }}
                                                    </span>
                                                    <div class="text-xs text-gray-500">{{ $subscription->months }} bulan
                                                    </div>
                                                @else
                                                    <span class="text-sm text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4">
                                                @if ($subscription)
                                                    <div class="flex items-center gap-2">
                                                        <i
                                                            class="fa-solid fa-calendar {{ $isExpired ? 'text-red-600' : ($daysRemaining <= 7 ? 'text-orange-600' : 'text-green-600') }}"></i>
                                                        <div>
                                                            <span class="text-sm text-gray-900">
                                                                {{ $subscription->expired_at->format('d M Y') }}
                                                            </span>
                                                            <div class="text-xs {{ $isExpired ? 'text-red-600' : ($daysRemaining <= 7 ? 'text-orange-600' : 'text-gray-500') }}">
                                                                @if ($isExpired)
                                                                    Expired {{ $subscription->expired_at->diffForHumans() }}
                                                                @else
                                                                    {{ $daysRemaining }} hari lagi
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-sm text-gray-400">No subscription</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                @if ($subscription && !$isExpired)
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $daysRemaining <= 7 ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800' }}">
                                                        <i
                                                            class="fa-solid {{ $daysRemaining <= 7 ? 'fa-clock' : 'fa-check-circle' }} mr-1"></i>
                                                        {{ $daysRemaining <= 7 ? 'Expiring Soon' : 'Active' }}
                                                    </span>
                                                @elseif($subscription && $isExpired)
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                        <i class="fa-solid fa-times-circle mr-1"></i> Expired
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                                        <i class="fa-solid fa-ban mr-1"></i> No Subscription
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <button type="button" onclick="editSubscription({{ $cluster->id }})"
                                                    class="px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:shadow-lg transition text-xs font-semibold">
                                                    <i class="fa-solid fa-edit mr-1"></i>
                                                    {{ $subscription && !$isExpired ? 'Extend' : 'Set Subscription' }}
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                                                <i class="fa-solid fa-inbox text-6xl text-gray-300 mb-4"></i>
                                                <p class="text-lg font-semibold">Tidak ada data cluster</p>
                                                <p class="text-sm">Belum ada cluster terdaftar dalam sistem</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if ($clusters->hasPages())
                            <div class="px-4 py-4 border-t border-gray-200">
                                {{ $clusters->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Edit Subscription -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50"
        style="display: none;">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4 flex items-center justify-between">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-edit"></i>
                    Edit Subscription
                </h3>
                <button type="button" onclick="closeEditModal()" class="text-white hover:text-gray-200 transition">
                    <i class="fa-solid fa-times text-2xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="subscriptionForm" onsubmit="submitSubscription(event)" class="p-6 space-y-6">
                <input type="hidden" id="cluster-id" name="cluster_id">

                <!-- Cluster Info -->
                <div class="bg-purple-50 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center">
                            <i class="fa-solid fa-building text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800" id="modal-cluster-name">-</h4>
                            <p class="text-sm text-gray-600" id="modal-cluster-email">-</p>
                        </div>
                    </div>
                </div>

                <!-- Current Subscription Info -->
                <div id="current-subscription-info" class="bg-blue-50 rounded-lg p-4" style="display: none;">
                    <h5 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-info-circle text-blue-600"></i>
                        Current Subscription
                    </h5>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-gray-600">Amount:</span>
                            <span class="font-semibold text-gray-800 ml-2" id="current-amount">-</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Expired At:</span>
                            <span class="font-semibold text-gray-800 ml-2" id="current-expired">-</span>
                        </div>
                    </div>
                </div>

                <!-- Subscription Amount -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fa-solid fa-money-bill-wave mr-1"></i> Subscription Amount
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">Rp</span>
                        <input type="number" id="amount" name="amount" required min="0" step="1000"
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                            placeholder="0">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Masukkan nominal subscription (bisa custom sesuai kebutuhan)
                    </p>
                </div>

                <!-- Extend Type -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fa-solid fa-calendar-plus mr-1"></i> Extend Type
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <label
                            class="relative flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-purple-500 transition group">
                            <input type="radio" name="extend_type" value="manual" checked
                                onchange="toggleExtendType('manual')"
                                class="w-4 h-4 text-purple-600 focus:ring-purple-500">
                            <div class="ml-3">
                                <div class="text-sm font-semibold text-gray-800 group-hover:text-purple-600">Manual
                                </div>
                                <div class="text-xs text-gray-500">Set tanggal expired manual</div>
                            </div>
                        </label>
                        <label
                            class="relative flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-purple-500 transition group">
                            <input type="radio" name="extend_type" value="automatic"
                                onchange="toggleExtendType('automatic')"
                                class="w-4 h-4 text-purple-600 focus:ring-purple-500">
                            <div class="ml-3">
                                <div class="text-sm font-semibold text-gray-800 group-hover:text-purple-600">Automatic
                                </div>
                                <div class="text-xs text-gray-500">Extend berdasarkan bulan</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Manual Date -->
                <div id="manual-date-group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fa-solid fa-calendar-day mr-1"></i> Expired Date
                    </label>
                    <input type="date" id="expired_date" name="expired_date"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                        min="{{ now()->addDay()->format('Y-m-d') }}">
                    <p class="text-xs text-gray-500 mt-1">Pilih tanggal expired subscription</p>
                </div>

                <!-- Automatic Months -->
                <div id="automatic-months-group" style="display: none;">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fa-solid fa-calendar-check mr-1"></i> Extend Duration
                    </label>
                    <select id="months" name="months"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                        <option value="1">1 Bulan</option>
                        <option value="3">3 Bulan</option>
                        <option value="6">6 Bulan</option>
                        <option value="12" selected>12 Bulan (1 Tahun)</option>
                        <option value="24">24 Bulan (2 Tahun)</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Subscription akan otomatis di-extend dari tanggal sekarang</p>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeEditModal()"
                        class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                        <i class="fa-solid fa-times mr-1"></i> Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:shadow-lg transition font-semibold">
                        <i class="fa-solid fa-save mr-1"></i> Save Subscription
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentClusterId = null;

        function editSubscription(clusterId) {
            currentClusterId = clusterId;
            fetch(`/admin/finance/subscription/${clusterId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Populate cluster info
                        document.getElementById('cluster-id').value = clusterId;
                        document.getElementById('modal-cluster-name').textContent = data.cluster.name;
                        document.getElementById('modal-cluster-email').textContent = data.cluster.email || '-';

                        // Show current subscription if exists
                        if (data.subscription) {
                            document.getElementById('current-subscription-info').style.display = 'block';
                            document.getElementById('current-amount').textContent = 'Rp ' + Number(data.subscription
                                .price).toLocaleString('id-ID');
                            document.getElementById('current-expired').textContent = new Date(data.subscription
                                .expired_at).toLocaleDateString('id-ID');

                            // Pre-fill amount
                            document.getElementById('amount').value = data.subscription.price;
                        } else {
                            document.getElementById('current-subscription-info').style.display = 'none';
                            document.getElementById('amount').value = '';
                        }

                        // Reset form
                        document.getElementById('subscriptionForm').reset();
                        document.querySelector('input[name="extend_type"][value="manual"]').checked = true;
                        toggleExtendType('manual');

                        // Show modal
                        document.getElementById('editModal').style.display = 'flex';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memuat data cluster');
                });
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
            currentClusterId = null;
        }

        function toggleExtendType(type) {
            const manualGroup = document.getElementById('manual-date-group');
            const automaticGroup = document.getElementById('automatic-months-group');
            const expiredDateInput = document.getElementById('expired_date');
            const monthsSelect = document.getElementById('months');

            if (type === 'manual') {
                manualGroup.style.display = 'block';
                automaticGroup.style.display = 'none';
                expiredDateInput.required = true;
                monthsSelect.required = false;
            } else {
                manualGroup.style.display = 'none';
                automaticGroup.style.display = 'block';
                expiredDateInput.required = false;
                monthsSelect.required = true;
            }
        }

        function submitSubscription(event) {
            event.preventDefault();

            const formData = new FormData(event.target);
            const data = {
                amount: formData.get('amount'),
                extend_type: formData.get('extend_type'),
                expired_date: formData.get('expired_date'),
                months: formData.get('months'),
                _token: '{{ csrf_token() }}'
            };

            fetch(`/admin/finance/subscription/${currentClusterId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        closeEditModal();
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal update subscription');
                });
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeEditModal();
            }
        });
    </script>
@endsection
