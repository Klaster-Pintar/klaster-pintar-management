<div>
    <div class="mb-6">
        <h3 class="text-xl lg:text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-shield-halved text-indigo-600"></i>
            <span>Data Security Cluster</span>
        </h3>
        <p class="text-sm text-gray-600 mt-1">Tambahkan petugas security yang bertugas di cluster</p>
    </div>

    <div class="space-y-4">
        <template x-for="(security, index) in formData.securities" :key="index">
            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-gray-800">
                        <i class="fa-solid fa-user-shield text-indigo-600 mr-1"></i>
                        Security <span x-text="index + 1"></span>
                    </h4>
                    <button type="button" @click="removeSecurity(index)"
                        class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
                        <i class="fa-solid fa-trash text-sm"></i>
                    </button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" :name="'securities['+index+'][name]'" x-model="security.name" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Nama lengkap security">
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" :name="'securities['+index+'][username]'" x-model="security.username"
                            required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="username_unik">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Email
                        </label>
                        <input type="email" :name="'securities['+index+'][email]'" x-model="security.email"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="email@example.com">
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            No. Telepon
                        </label>
                        <input type="text" :name="'securities['+index+'][phone]'" x-model="security.phone"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="08123456789">
                    </div>

                    <!-- Password -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" :name="'securities['+index+'][password]'" x-model="security.password"
                            required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Min. 8 karakter">
                    </div>
                </div>
            </div>
        </template>

        <!-- Add Security Button -->
        <button type="button" @click="addSecurity"
            class="w-full px-4 py-3 border-2 border-dashed border-indigo-300 text-indigo-600 rounded-lg hover:bg-indigo-50 transition font-semibold">
            <i class="fa-solid fa-shield-halved mr-1"></i> Tambah Security
        </button>

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex gap-3">
                <i class="fa-solid fa-info-circle text-blue-600 text-lg"></i>
                <div class="text-sm text-gray-700">
                    <p class="font-semibold mb-1">Informasi Penting:</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        <li>Setiap security yang ditambahkan akan otomatis dibuatkan akun user</li>
                        <li>Role security akan otomatis di-set sebagai "SECURITY"</li>
                        <li>Security dapat login menggunakan username dan password yang dibuat</li>
                        <li>Username harus unik dan belum terdaftar di sistem</li>
                        <li>Anda bisa skip langkah ini dan menambahkan security nanti</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>