<div>
    <div class="mb-6">
        <h3 class="text-xl lg:text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-route text-orange-600"></i>
            <span>Titik Patroli Security</span>
        </h3>
        <p class="text-sm text-gray-600 mt-1">Tentukan titik-titik patroli security (opsional)</p>
    </div>

    <div class="space-y-4">
        <!-- Info Box -->
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
            <div class="flex gap-3">
                <i class="fa-solid fa-lightbulb text-orange-600 text-lg"></i>
                <div class="text-sm text-gray-700">
                    <p class="font-semibold mb-1">Tentang Titik Patroli:</p>
                    <p class="text-xs">Titik patroli adalah lokasi-lokasi yang harus dikunjungi security saat melakukan
                        patroli. Anda bisa menambahkan multiple titik patroli yang berbeda untuk setiap jenis hari
                        (Weekday/Weekend).</p>
                </div>
            </div>
        </div>

        <!-- Patrol Configuration (Mock UI for now) -->
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
            <div class="text-center text-gray-600">
                <i class="fa-solid fa-map-marked-alt text-6xl text-gray-300 mb-4"></i>
                <p class="font-semibold mb-2">Fitur Titik Patroli</p>
                <p class="text-sm text-gray-500 mb-4">Tambahkan multiple marker di peta untuk menentukan rute patroli
                    security</p>

                <!-- Mock Patrol Form -->
                <div class="max-w-md mx-auto mt-6 text-left">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Tipe Hari
                    </label>
                    <select
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition mb-4">
                        <option value="1">Weekday (Senin - Jumat)</option>
                        <option value="2">Weekend (Sabtu - Minggu)</option>
                    </select>

                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                        <i class="fa-solid fa-map text-4xl text-gray-300 mb-2"></i>
                        <p class="text-sm text-gray-500">Integrasi Google Maps</p>
                        <button type="button"
                            class="mt-3 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition text-sm">
                            <i class="fa-solid fa-map-pin mr-1"></i> Tambah Titik Patroli
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Skip Option -->
        <div class="bg-gray-100 border border-gray-300 rounded-lg p-4 text-center">
            <p class="text-sm text-gray-600">
                <i class="fa-solid fa-info-circle text-gray-500 mr-1"></i>
                Anda bisa melewati langkah ini dan mengatur titik patroli nanti dari menu pengaturan cluster.
            </p>
        </div>
    </div>
</div>