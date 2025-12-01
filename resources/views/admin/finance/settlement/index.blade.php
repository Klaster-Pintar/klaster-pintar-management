@extends('layouts.app')

@section('title', 'Settlement - Finance Management')

@section('content')
    <div class="flex min-h-screen bg-gradient-to-br from-gray-50 to-blue-50" x-data="{ sidebarOpen: false }">
        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <!-- Sidebar Component -->
        <x-admin.sidebar activeMenu="finance.settlement" />

        <!-- Main Wrapper -->
        <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">
            <!-- Header Component -->
            <x-admin.header />

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <div class="w-full space-y-4">
                    <!-- Page Header -->
                    <div class="bg-white rounded-xl shadow-lg p-4 lg:p-6">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-green-100 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-money-bill-transfer text-green-600 text-lg lg:text-xl"></i>
                            </div>
                            <div>
                                <h1 class="text-xl lg:text-2xl font-bold text-gray-800">Settlement Management</h1>
                                <p class="text-xs lg:text-sm text-gray-600 mt-0.5">Kelola penarikan saldo cluster dan
                                    approval settlement</p>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6">
                        <form method="GET" action="{{ route('admin.finance.settlement.index') }}"
                            class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                            <!-- Search -->
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fa-solid fa-search mr-1"></i> Cari Cluster / Kode
                                </label>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                                    placeholder="Nama cluster atau kode transaksi...">
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fa-solid fa-filter mr-1"></i> Status
                                </label>
                                <select name="status"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                                    </option>
                                </select>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-end">
                                <button type="submit"
                                    class="w-full px-6 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:shadow-lg transition font-semibold">
                                    <i class="fa-solid fa-search mr-1"></i> Filter
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Settlements Table -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-green-600 to-green-700 text-white">
                                    <tr>
                                        <th class="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider">No
                                        </th>
                                        <th class="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider">
                                            Tanggal</th>
                                        <th class="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider">Kode
                                        </th>
                                        <th class="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider">
                                            Cluster</th>
                                        <th class="px-4 py-4 text-right text-xs font-semibold uppercase tracking-wider">
                                            Amount</th>
                                        <th class="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider">
                                            Status</th>
                                        <th class="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider">
                                            Description</th>
                                        <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($settlements as $index => $settlement)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-4 py-4 text-sm text-gray-700">
                                                {{ $settlements->firstItem() + $index }}
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-700">
                                                <div class="flex items-center gap-2">
                                                    <i class="fa-solid fa-calendar text-green-600"></i>
                                                    <span>{{ $settlement->created_at->format('d M Y') }}</span>
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $settlement->created_at->format('H:i') }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-sm font-mono text-gray-700">
                                                {{ $settlement->transaction_code }}
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-900 font-medium">
                                                {{ $settlement->cluster->name ?? '-' }}
                                            </td>
                                            <td class="px-4 py-4 text-sm text-right font-semibold text-green-700">
                                                Rp {{ number_format($settlement->amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-4 text-sm">
                                                @if($settlement->is_valid == 1)
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                        <i class="fa-solid fa-check-circle mr-1"></i> Approved
                                                    </span>
                                                @elseif($settlement->status == 'REJECTED')
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                        <i class="fa-solid fa-times-circle mr-1"></i> Rejected
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                        <i class="fa-solid fa-clock mr-1"></i> Pending
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-600 max-w-xs truncate">
                                                {{ $settlement->description ?? '-' }}
                                            </td>
                                            <td class="px-4 py-4 text-sm text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <!-- View Detail Button -->
                                                    <button type="button" onclick="viewDetail({{ $settlement->id }})"
                                                        class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition text-xs font-semibold">
                                                        <i class="fa-solid fa-eye mr-1"></i> Detail
                                                    </button>

                                                    @if($settlement->is_valid == 0 && $settlement->status != 'REJECTED')
                                                        <!-- Approve Button -->
                                                        <button type="button" onclick="approveSettlement({{ $settlement->id }})"
                                                            class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition text-xs font-semibold">
                                                            <i class="fa-solid fa-check mr-1"></i> Approve
                                                        </button>

                                                        <!-- Reject Button -->
                                                        <button type="button" onclick="rejectSettlement({{ $settlement->id }})"
                                                            class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition text-xs font-semibold">
                                                            <i class="fa-solid fa-times mr-1"></i> Reject
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="px-4 py-12 text-center text-gray-500">
                                                <i class="fa-solid fa-inbox text-6xl text-gray-300 mb-4"></i>
                                                <p class="text-lg font-semibold">Tidak ada data settlement</p>
                                                <p class="text-sm">Belum ada transaksi penarikan yang perlu diproses</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($settlements->hasPages())
                            <div class="px-4 py-4 border-t border-gray-200">
                                {{ $settlements->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Detail SETORAN -->
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50"
        style="display: none;">
        <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 flex items-center justify-between">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    Detail Setoran
                </h3>
                <button type="button" onclick="closeDetailModal()" class="text-white hover:text-gray-200 transition">
                    <i class="fa-solid fa-times text-2xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 80px);">
                <!-- Withdrawal Info -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-info-circle text-blue-600"></i>
                        Informasi Penarikan
                    </h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Cluster:</span>
                            <span class="font-semibold text-gray-800 ml-2" id="modal-cluster-name">-</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Kode:</span>
                            <span class="font-mono text-gray-800 ml-2" id="modal-transaction-code">-</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Tanggal:</span>
                            <span class="text-gray-800 ml-2" id="modal-transaction-date">-</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Total Amount:</span>
                            <span class="font-bold text-green-700 ml-2" id="modal-amount">-</span>
                        </div>
                    </div>
                </div>

                <!-- SETORAN Table -->
                <h4 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-list text-green-600"></i>
                    Daftar Setoran
                </h4>
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Description
                                </th>
                            </tr>
                        </thead>
                        <tbody id="setoran-tbody" class="bg-white divide-y divide-gray-200">
                            <!-- Data will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 flex justify-end border-t border-gray-200">
                <button type="button" onclick="closeDetailModal()"
                    class="px-6 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-semibold">
                    <i class="fa-solid fa-times mr-1"></i> Close
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Reject -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50"
        style="display: none;">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 flex items-center justify-between">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-times-circle"></i>
                    Reject Settlement
                </h3>
                <button type="button" onclick="closeRejectModal()" class="text-white hover:text-gray-200 transition">
                    <i class="fa-solid fa-times text-2xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <p class="text-gray-700 mb-4">Berikan alasan penolakan settlement ini:</p>
                <textarea id="reject-reason" rows="4"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                    placeholder="Alasan penolakan..."></textarea>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-2 border-t border-gray-200">
                <button type="button" onclick="closeRejectModal()"
                    class="px-6 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-semibold">
                    Batal
                </button>
                <button type="button" onclick="confirmReject()"
                    class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold">
                    <i class="fa-solid fa-times mr-1"></i> Reject
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let currentRejectId = null;

            function viewDetail(id) {
                fetch(`/admin/finance/settlement/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Populate withdrawal info
                            document.getElementById('modal-cluster-name').textContent = data.withdrawal.cluster.name;
                            document.getElementById('modal-transaction-code').textContent = data.withdrawal.transaction_code;
                            document.getElementById('modal-transaction-date').textContent = new Date(data.withdrawal.created_at).toLocaleDateString('id-ID');
                            document.getElementById('modal-amount').textContent = 'Rp ' + Number(data.withdrawal.amount).toLocaleString('id-ID');

                            // Populate setoran table
                            const tbody = document.getElementById('setoran-tbody');
                            tbody.innerHTML = '';

                            if (data.setorans.length === 0) {
                                tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Tidak ada data setoran</td></tr>';
                            } else {
                                data.setorans.forEach((setoran, index) => {
                                    const date = new Date(setoran.created_at);
                                    const dateStr = date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                                    const timeStr = date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                                    const row = `
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-4 py-3 text-sm text-gray-700">${index + 1}</td>
                                                        <td class="px-4 py-3 text-sm text-gray-700">
                                                            <div>${dateStr}</div>
                                                            <div class="text-xs text-gray-500">${timeStr}</div>
                                                        </td>
                                                        <td class="px-4 py-3 text-sm text-right font-semibold text-green-700">Rp ${Number(setoran.amount).toLocaleString('id-ID')}</td>
                                                        <td class="px-4 py-3 text-sm text-gray-600">${setoran.description || '-'}</td>
                                                    </tr>
                                                `;
                                    tbody.innerHTML += row;
                                });
                            }

                            // Show modal
                            document.getElementById('detailModal').style.display = 'flex';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal memuat detail settlement');
                    });
            }

            function closeDetailModal() {
                document.getElementById('detailModal').style.display = 'none';
            }

            function approveSettlement(id) {
                if (!confirm('Yakin ingin approve settlement ini?')) return;

                fetch(`/admin/finance/settlement/${id}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal approve settlement');
                    });
            }

            function rejectSettlement(id) {
                currentRejectId = id;
                document.getElementById('reject-reason').value = '';
                document.getElementById('rejectModal').style.display = 'flex';
            }

            function closeRejectModal() {
                document.getElementById('rejectModal').style.display = 'none';
                currentRejectId = null;
            }

            function confirmReject() {
                const reason = document.getElementById('reject-reason').value.trim();

                if (!reason) {
                    alert('Alasan penolakan harus diisi');
                    return;
                }

                fetch(`/admin/finance/settlement/${currentRejectId}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ reason: reason })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal reject settlement');
                    });
            }

            // Close modal when clicking outside
            document.getElementById('detailModal').addEventListener('click', function (e) {
                if (e.target === this) closeDetailModal();
            });

            document.getElementById('rejectModal').addEventListener('click', function (e) {
                if (e.target === this) closeRejectModal();
            });
        </script>
    @endpush
@endsection