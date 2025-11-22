<div>
    <div class="mb-6">
        <h3 class="text-xl lg:text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-building-columns text-emerald-600"></i>
            <span>Rekening Bank Cluster</span>
        </h3>
        <p class="text-sm text-gray-600 mt-1">Tambahkan rekening bank untuk transaksi cluster (minimal 1)</p>
    </div>

    <div class="space-y-4">
        <template x-for="(bank, index) in formData.bank_accounts" :key="index">
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-gray-800">
                        <i class="fa-solid fa-credit-card text-emerald-600 mr-1"></i>
                        Rekening Bank <span x-text="index + 1"></span>
                    </h4>
                    <button type="button" @click="removeBankAccount(index)" x-show="formData.bank_accounts.length > 1"
                        class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
                        <i class="fa-solid fa-trash text-sm"></i>
                    </button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <!-- Bank Type/Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Bank <span class="text-red-500">*</span>
                        </label>
                        <select :name="'bank_accounts['+index+'][bank_type]'" x-model="bank.bank_type" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                            <option value="BCA">BCA</option>
                            <option value="BRI">BRI</option>
                            <option value="BNI">BNI</option>
                            <option value="MANDIRI">Mandiri</option>
                            <option value="CIMB">CIMB Niaga</option>
                            <option value="PERMATA">Permata</option>
                            <option value="BTN">BTN</option>
                            <option value="DANAMON">Danamon</option>
                            <option value="BSI">Bank Syariah Indonesia (BSI)</option>
                            <option value="OTHERS">Lainnya</option>
                        </select>
                    </div>

                    <!-- Bank Code -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Kode Bank <span class="text-red-500">*</span>
                        </label>
                        <input type="number" :name="'bank_accounts['+index+'][bank_code_id]'"
                            x-model="bank.bank_code_id" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                            placeholder="014 (BCA), 002 (BRI), dst">
                    </div>

                    <!-- Account Number -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            No. Rekening <span class="text-red-500">*</span>
                        </label>
                        <input type="text" :name="'bank_accounts['+index+'][account_number]'"
                            x-model="bank.account_number" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                            placeholder="1234567890">
                    </div>

                    <!-- Account Holder -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Pemilik Rekening <span class="text-red-500">*</span>
                        </label>
                        <input type="text" :name="'bank_accounts['+index+'][account_holder]'"
                            x-model="bank.account_holder" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                            placeholder="Sesuai rekening bank">
                    </div>
                </div>
            </div>
        </template>

        <!-- Add Bank Account Button -->
        <button type="button" @click="addBankAccount"
            class="w-full px-4 py-3 border-2 border-dashed border-emerald-300 text-emerald-600 rounded-lg hover:bg-emerald-50 transition font-semibold">
            <i class="fa-solid fa-plus-circle mr-1"></i> Tambah Rekening Bank Lainnya
        </button>

        <!-- Common Bank Codes Reference -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex gap-3">
                <i class="fa-solid fa-info-circle text-blue-600 text-lg"></i>
                <div class="text-sm text-gray-700">
                    <p class="font-semibold mb-2">Kode Bank Umum:</p>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 text-xs">
                        <div><span class="font-semibold">BCA:</span> 014</div>
                        <div><span class="font-semibold">BRI:</span> 002</div>
                        <div><span class="font-semibold">BNI:</span> 009</div>
                        <div><span class="font-semibold">Mandiri:</span> 008</div>
                        <div><span class="font-semibold">CIMB:</span> 022</div>
                        <div><span class="font-semibold">Permata:</span> 013</div>
                        <div><span class="font-semibold">BTN:</span> 200</div>
                        <div><span class="font-semibold">BSI:</span> 451</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verification Notice -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex gap-3">
                <i class="fa-solid fa-triangle-exclamation text-yellow-600 text-lg"></i>
                <div class="text-sm text-gray-700">
                    <p class="font-semibold mb-1">Perhatian:</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        <li>Pastikan nomor rekening dan nama pemilik rekening sudah benar</li>
                        <li>Rekening akan diverifikasi oleh admin sebelum dapat digunakan</li>
                        <li>Rekening ini akan digunakan untuk transaksi pembayaran cluster</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>