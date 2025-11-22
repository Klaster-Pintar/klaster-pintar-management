<div>
    <div class="mb-6">
        <h3 class="text-xl lg:text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-building text-green-600"></i>
            <span>Data Kantor/Office Cluster</span>
        </h3>
        <p class="text-sm text-gray-600 mt-1">Tambahkan lokasi kantor atau pos dalam cluster (minimal 1)</p>
    </div>

    <div class="space-y-4">
        <template x-for="(office, index) in formData.offices" :key="index">
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-gray-800">
                        <i class="fa-solid fa-building text-green-600 mr-1"></i>
                        Office <span x-text="index + 1"></span>
                    </h4>
                    <button type="button" @click="removeOffice(index)" x-show="formData.offices.length > 1"
                        class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
                        <i class="fa-solid fa-trash text-sm"></i>
                    </button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <!-- Office Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Office <span class="text-red-500">*</span>
                        </label>
                        <input type="text" :name="'offices['+index+'][name]'" x-model="office.name" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                            placeholder="Contoh: Pos Security Utama">
                    </div>

                    <!-- Office Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tipe Office <span class="text-red-500">*</span>
                        </label>
                        <select :name="'offices['+index+'][type_id]'" x-model="office.type_id" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                            <option value="1">Pos Security</option>
                            <option value="2">Kantor Pengelola</option>
                            <option value="3">Sekretariat</option>
                            <option value="4">Lainnya</option>
                        </select>
                    </div>

                    <!-- Latitude -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Latitude <span class="text-red-500">*</span>
                        </label>
                        <input type="text" :name="'offices['+index+'][latitude]'" x-model="office.latitude" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                            placeholder="-6.200000">
                    </div>

                    <!-- Longitude -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Longitude <span class="text-red-500">*</span>
                        </label>
                        <input type="text" :name="'offices['+index+'][longitude]'" x-model="office.longitude" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                            placeholder="106.816666">
                    </div>
                </div>

                <div class="mt-3">
                    <button type="button" class="text-sm text-blue-600 hover:text-blue-800 transition">
                        <i class="fa-solid fa-map-location-dot mr-1"></i> Pilih dari Peta Google Maps
                    </button>
                </div>
            </div>
        </template>

        <!-- Add Office Button -->
        <button type="button" @click="addOffice"
            class="w-full px-4 py-3 border-2 border-dashed border-green-300 text-green-600 rounded-lg hover:bg-green-50 transition font-semibold">
            <i class="fa-solid fa-plus-circle mr-1"></i> Tambah Office Lainnya
        </button>

        <!-- Helper Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex gap-3">
                <i class="fa-solid fa-info-circle text-blue-600 text-lg"></i>
                <div class="text-sm text-gray-700">
                    <p class="font-semibold mb-1">Tips mendapatkan koordinat:</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        <li>Buka Google Maps dan cari lokasi office Anda</li>
                        <li>Klik kanan pada titik lokasi → Pilih "What's here?"</li>
                        <li>Koordinat akan muncul di bagian bawah (Latitude, Longitude)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>