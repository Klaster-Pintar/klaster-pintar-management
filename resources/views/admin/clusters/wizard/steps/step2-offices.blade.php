<div>
    <div class="mb-6">
        <h3 class="text-xl lg:text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-building text-green-600"></i>
            <span>Data Kantor/Office Cluster</span>
        </h3>
        <p class="text-sm text-gray-600 mt-1">Gunakan peta di bawah untuk menandai lokasi kantor atau pos dalam cluster
        </p>
    </div>

    <!-- Google Maps Container -->
    <div class="mb-6">
        <!-- Search Box -->
        <div class="mb-4">
            <input id="officeSearchInput" type="text"
                class="w-full px-4 py-3 border-2 border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                placeholder="🔍 Cari lokasi kantor... (contoh: Monas, Jakarta)">
        </div>

        <!-- Map Controls -->
        <div class="flex flex-wrap gap-2 mb-3">
            <button type="button" @click="clearAllOfficeMarkers()"
                class="px-4 py-2 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600 transition flex items-center gap-2">
                <i class="fa-solid fa-trash"></i>
                <span>Clear All Markers</span>
            </button>
            <div class="flex-1"></div>
            <div class="text-sm text-gray-600 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-blue-500"></i>
                <span>Total: <strong x-text="formData.offices.length"></strong> kantor</span>
            </div>
        </div>

        <!-- Google Map -->
        <div id="officeMap" class="map-container"></div>

        <!-- Map Instructions -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mt-4">
            <div class="flex gap-3">
                <i class="fa-solid fa-lightbulb text-green-600 text-lg"></i>
                <div class="text-sm text-gray-700">
                    <p class="font-semibold mb-2">Cara Menggunakan Peta:</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        <li><strong>Cari Lokasi:</strong> Gunakan search box di atas untuk mencari alamat</li>
                        <li><strong>Klik Peta:</strong> Klik pada peta untuk menambah marker kantor</li>
                        <li><strong>Drag Marker:</strong> Drag marker (hijau) untuk mengubah posisi</li>
                        <li><strong>Info Marker:</strong> Klik marker untuk melihat info dan tombol hapus</li>
                        <li><strong>Edit Detail:</strong> Isi nama dan tipe kantor di form di bawah</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Office List with Details -->
    <div class="space-y-4" x-show="formData.offices.length > 0">
        <h4 class="font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-list-check text-green-600"></i>
            <span>Daftar Kantor & Detail</span>
        </h4>

        <template x-for="(office, index) in formData.offices" :key="index">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <h5 class="font-semibold text-gray-800 flex items-center gap-2">
                        <span
                            class="w-6 h-6 bg-green-600 text-white rounded-full flex items-center justify-center text-xs"
                            x-text="index + 1"></span>
                        <span>Office <span x-text="index + 1"></span></span>
                    </h5>
                    <button type="button" @click="deleteOfficeMarker(index)"
                        class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
                        <i class="fa-solid fa-trash text-sm"></i>
                    </button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <!-- Office Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Kantor <span class="text-red-500">*</span>
                        </label>
                        <input type="text" :name="'offices['+index+'][name]'" x-model="office.name" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                            placeholder="Contoh: Pos Security Utama">
                    </div>

                    <!-- Office Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tipe Kantor <span class="text-red-500">*</span>
                        </label>
                        <select :name="'offices['+index+'][type_id]'" x-model="office.type_id" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                            <option value="1">Pos Security</option>
                            <option value="2">Kantor Pengelola</option>
                            <option value="3">Sekretariat</option>
                            <option value="4">Lainnya</option>
                        </select>
                    </div>

                    <!-- Coordinates (Read-only, from map) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Koordinat (dari peta)
                        </label>
                        <input type="hidden" :name="'offices['+index+'][latitude]'" x-model="office.latitude">
                        <input type="hidden" :name="'offices['+index+'][longitude]'" x-model="office.longitude">
                        <div
                            class="w-full px-4 py-2.5 bg-gray-100 border border-gray-300 rounded-lg text-sm text-gray-600">
                            <i class="fa-solid fa-map-pin text-green-600 mr-1"></i>
                            <span
                                x-text="office.latitude ? office.latitude + ', ' + office.longitude : 'Pilih dari peta'"></span>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Empty State -->
    <div x-show="formData.offices.length === 0"
        class="text-center py-12 bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg">
        <i class="fa-solid fa-map-location-dot text-6xl text-gray-300 mb-4"></i>
        <p class="text-gray-600 font-semibold mb-2">Belum ada kantor yang ditandai</p>
        <p class="text-sm text-gray-500">Klik pada peta atau gunakan search box untuk menambah lokasi kantor</p>
    </div>
</div>