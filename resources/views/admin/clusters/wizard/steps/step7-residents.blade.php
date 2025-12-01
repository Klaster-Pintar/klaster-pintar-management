<div class="space-y-6">
    <div class="flex items-center gap-3 pb-4 border-b border-gray-200">
        <div
            class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
            <i class="fa-solid fa-users text-white text-xl"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-gray-800">Step 7: Data Residents</h2>
            <p class="text-sm text-gray-600">Upload data warga/penghuni cluster via CSV</p>
        </div>
    </div>

    <!-- Upload Section -->
    <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl p-6 border-2 border-dashed border-purple-300"
        x-show="!csvPreviewData.length">
        <div class="text-center">
            <div class="mb-4">
                <i class="fa-solid fa-file-csv text-6xl text-purple-500"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Upload File CSV Residents</h3>
            <p class="text-sm text-gray-600 mb-4">
                Format: Nama, No HP, Blok, Nomor, Status Rumah, Status User, Nominal IPL
            </p>

            <!-- Download Template -->
            <div class="mb-4">
                <button type="button" @click="downloadCsvTemplate"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                    <i class="fa-solid fa-download"></i>
                    <span>Download Template CSV</span>
                </button>
            </div>

            <!-- File Input -->
            <div class="relative">
                <input type="file" id="csvFileInput" accept=".csv" @change="handleCsvUpload" class="hidden">
                <label for="csvFileInput"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:shadow-lg transition cursor-pointer font-semibold">
                    <i class="fa-solid fa-upload"></i>
                    <span>Pilih File CSV</span>
                </label>
            </div>

            <p class="text-xs text-gray-500 mt-3">
                <i class="fa-solid fa-info-circle"></i> File maksimal 2MB, format CSV dengan delimiter koma (,)
            </p>
        </div>
    </div>

    <!-- CSV Preview & Validation -->
    <div x-show="csvPreviewData.length > 0" x-transition class="space-y-4">
        <!-- Header Info -->
        <div class="flex items-center justify-between bg-purple-100 rounded-lg p-4">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-file-csv text-2xl text-purple-600"></i>
                <div>
                    <h4 class="font-semibold text-gray-800">File: <span x-text="csvFileName"></span></h4>
                    <p class="text-sm text-gray-600">
                        Total: <span x-text="csvPreviewData.length"></span> residents |
                        Valid: <span class="text-green-600 font-semibold" x-text="csvValidCount"></span> |
                        Error: <span class="text-red-600 font-semibold" x-text="csvErrorCount"></span>
                    </p>
                </div>
            </div>
            <button type="button" @click="clearCsvData"
                class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                <i class="fa-solid fa-times mr-1"></i> Hapus
            </button>
        </div>

        <!-- Validation Summary -->
        <div x-show="csvErrorCount > 0" class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-exclamation-triangle text-red-600 text-xl mt-0.5"></i>
                <div class="flex-1">
                    <h5 class="font-semibold text-red-800 mb-2">Ditemukan <span x-text="csvErrorCount"></span> Error
                    </h5>
                    <ul class="text-sm text-red-700 space-y-1 list-disc list-inside">
                        <template x-for="error in csvValidationErrors" :key="error">
                            <li x-text="error"></li>
                        </template>
                    </ul>
                    <p class="text-xs text-red-600 mt-2">
                        <i class="fa-solid fa-info-circle"></i> Perbaiki error sebelum melanjutkan. Data dengan error
                        akan di-skip saat save.
                    </p>
                </div>
            </div>
        </div>

        <!-- Data Preview Table -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto" style="max-height: 400px;">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white sticky top-0 z-10">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider">No</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">No HP</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider">Blok</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nomor</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status Rumah
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status User
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nominal IPL
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Keterangan
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="(row, index) in csvPreviewData" :key="index">
                            <tr :class="row.isValid ? 'hover:bg-gray-50' : 'bg-red-50 hover:bg-red-100'">
                                <td class="px-3 py-3 text-sm text-gray-700" x-text="index + 1"></td>
                                <td class="px-3 py-3">
                                    <span x-show="row.isValid"
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <i class="fa-solid fa-check-circle mr-1"></i> Valid
                                    </span>
                                    <span x-show="!row.isValid"
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        <i class="fa-solid fa-times-circle mr-1"></i> Error
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 font-medium" x-text="row.name"></td>
                                <td class="px-4 py-3 text-sm text-gray-700" x-text="row.phone"></td>
                                <td class="px-3 py-3 text-sm text-gray-700" x-text="row.house_block"></td>
                                <td class="px-3 py-3 text-sm text-gray-700" x-text="row.house_number"></td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold"
                                        :class="['HUNI', 'Milik'].includes(row.house_status) ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800'"
                                        x-text="row.house_status">
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold"
                                        :class="row.user_status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                        x-text="row.user_status">
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    <span x-text="formatRupiah(row.nominal_ipl)"></span>
                                </td>
                                <td class="px-4 py-3 text-xs" :class="row.isValid ? 'text-gray-500' : 'text-red-600'">
                                    <span x-text="row.error || '-'"></span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between bg-gray-50 rounded-lg p-4">
            <div class="text-sm text-gray-600">
                <i class="fa-solid fa-info-circle text-blue-500"></i>
                Data akan diproses saat klik <strong>Selesai & Simpan</strong> di bawah
            </div>
            <button type="button" @click="validateCsvData"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fa-solid fa-check-double mr-1"></i> Validasi Ulang
            </button>
        </div>
    </div>

    <!-- Hidden Input for CSV Data -->
    <input type="hidden" name="residents_csv_data" :value="JSON.stringify(csvPreviewData.filter(r => r.isValid))">

    <!-- Step Instruction -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex gap-3">
            <i class="fa-solid fa-lightbulb text-blue-600 text-xl"></i>
            <div class="flex-1">
                <h5 class="font-semibold text-blue-900 mb-1">Panduan Upload CSV</h5>
                <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside">
                    <li>Download template CSV terlebih dahulu untuk mendapatkan format yang benar</li>
                    <li>Kolom wajib diisi: Nama, No HP, Blok, Nomor</li>
                    <li>Status Rumah: "HUNI" atau "KOSONG"</li>
                    <li>Status User: "Active" atau "Inactive"</li>
                    <li>No HP harus berisi angka saja tanpa spasi atau karakter lain</li>
                    <li>Nominal IPL harus berupa angka tanpa titik, koma, atau pemisah ribuan</li>
                    <li>No HP akan digunakan sebagai username dan akan di-validasi duplikasi</li>
                    <li>Jika user dengan No HP sudah ada, akan menggunakan user existing (tidak create baru)</li>
                    <li>Jika rumah sudah terisi (house_id sama & tidak deleted), akan muncul warning</li>
                    <li>Password default akan di-generate otomatis untuk user baru</li>
                </ul>
            </div>
        </div>
    </div>
</div>