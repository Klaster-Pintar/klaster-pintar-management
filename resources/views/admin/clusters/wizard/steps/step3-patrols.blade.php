<div>
    <div class="mb-6">
        <h3 class="text-xl lg:text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-route text-orange-600"></i>
            <span>Titik Patroli Security</span>
        </h3>
        <p class="text-sm text-gray-600 mt-1">Tentukan rute patroli security dengan menandai multiple lokasi di peta</p>
    </div>

    <div class="space-y-4">
        <!-- Day Type Selector -->
        <div class="bg-white border border-orange-200 rounded-lg p-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fa-solid fa-calendar-days text-orange-600 mr-1"></i>
                Tipe Hari Patroli
            </label>
            <select x-model="currentPatrolIndex"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                <template x-for="(patrol, index) in formData.patrols" :key="index">
                    <option :value="index"
                        x-text="patrol.day_type_id === 1 ? 'Weekday (Senin - Jumat)' : 'Weekend (Sabtu - Minggu)'">
                    </option>
                </template>
            </select>
        </div>

        <!-- Map Controls -->
        <div class="flex flex-wrap gap-2 mb-3">
            <button type="button" @click="clearAllPatrolMarkers()"
                class="px-4 py-2 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600 transition flex items-center gap-2">
                <i class="fa-solid fa-trash"></i>
                <span>Clear All Points</span>
            </button>
            <div class="flex-1"></div>
            <div class="text-sm text-gray-600 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-orange-500"></i>
                <span>Total: <strong x-text="patrolMarkers.length"></strong> titik patroli</span>
            </div>
        </div>

        <!-- Google Map -->
        <div id="patrolMap" class="map-container"></div>

        <!-- Map Instructions -->
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mt-4">
            <div class="flex gap-3">
                <i class="fa-solid fa-lightbulb text-orange-600 text-lg"></i>
                <div class="text-sm text-gray-700">
                    <p class="font-semibold mb-2">Cara Menggunakan Peta Patroli:</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        <li><strong>Klik Peta:</strong> Klik pada peta untuk menambah titik patroli (urutan: 1, 2, 3...)
                        </li>
                        <li><strong>Drag Marker:</strong> Drag marker (orange dengan nomor) untuk mengubah posisi</li>
                        <li><strong>Hapus Marker:</strong> Klik marker untuk melihat info dan tombol hapus</li>
                        <li><strong>Urutan:</strong> Nomor pada marker menunjukkan urutan rute patroli</li>
                        <li><strong>Clear:</strong> Gunakan tombol "Clear All Points" untuk menghapus semua marker</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Patrol Points List -->
        <div x-show="patrolMarkers.length > 0"
            class="bg-gradient-to-r from-orange-50 to-amber-50 border border-orange-200 rounded-lg p-4">
            <h4 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-list-ol text-orange-600"></i>
                <span>Rute Patroli (Urutan)</span>
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                <template x-for="(marker, index) in patrolMarkers" :key="index">
                    <div class="bg-white border border-orange-300 rounded-lg p-3 flex items-center gap-3">
                        <div
                            class="w-8 h-8 bg-orange-600 text-white rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0">
                            <span x-text="index + 1"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-800">Patrol Point <span x-text="index + 1"></span>
                            </p>
                            <p class="text-xs text-gray-600 truncate">
                                <span x-text="marker.lat.toFixed(7)"></span>,
                                <span x-text="marker.lng.toFixed(7)"></span>
                            </p>
                        </div>
                        <button type="button" @click="deletePatrolMarker(index)"
                            class="p-1.5 bg-red-100 text-red-600 rounded hover:bg-red-200 transition flex-shrink-0">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    </div>
                </template>
            </div>

            <!-- Hidden inputs for form submission -->
            <template x-for="(marker, index) in patrolMarkers" :key="index">
                <div>
                    <input type="hidden" :name="'patrols[0][pinpoints]['+index+'][lat]'" :value="marker.lat.toFixed(7)">
                    <input type="hidden" :name="'patrols[0][pinpoints]['+index+'][lng]'" :value="marker.lng.toFixed(7)">
                </div>
            </template>
            <input type="hidden" name="patrols[0][day_type_id]" value="1">
        </div>

        <!-- Empty State -->
        <div x-show="patrolMarkers.length === 0"
            class="text-center py-12 bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg">
            <i class="fa-solid fa-map-marked-alt text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-600 font-semibold mb-2">Belum ada titik patroli</p>
            <p class="text-sm text-gray-500 mb-4">Klik pada peta untuk menambah titik patroli security</p>
            <p class="text-xs text-gray-400 italic">💡 Titik patroli bersifat opsional, bisa dilewati jika belum ada
                rute patroli</p>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex gap-3">
                <i class="fa-solid fa-info-circle text-blue-600 text-lg"></i>
                <div class="text-sm text-gray-700">
                    <p class="font-semibold mb-1">Tentang Titik Patroli:</p>
                    <p class="text-xs">Titik patroli adalah lokasi-lokasi yang harus dikunjungi security saat melakukan
                        patroli.
                        Urutan nomor pada marker menunjukkan urutan rute yang harus dilalui. Anda dapat menambahkan
                        sebanyak mungkin titik
                        patroli sesuai kebutuhan. Fitur ini bersifat <strong>opsional</strong> dan bisa dikonfigurasi
                        nanti.</p>
                </div>
            </div>
        </div>
    </div>
</div>