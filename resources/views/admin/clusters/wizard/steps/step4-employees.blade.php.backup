<div>
    <div class="mb-6">
        <h3 class="text-xl lg:text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-users text-purple-600"></i>
            <span>Data Karyawan/Admin Cluster</span>
        </h3>
        <p class="text-sm text-gray-600 mt-1">Tambahkan karyawan yang akan mengelola cluster (RT, RW, ADMIN)</p>
    </div>

    <div class="space-y-4">
        <template x-for="(employee, index) in formData.employees" :key="index">
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-gray-800">
                        <i class="fa-solid fa-user-tie text-purple-600 mr-1"></i>
                        Karyawan <span x-text="index + 1"></span>
                    </h4>
                    <button type="button" @click="removeEmployee(index)"
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
                        <input type="text" :name="'employees['+index+'][name]'" x-model="employee.name" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                            placeholder="Nama lengkap karyawan">
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" :name="'employees['+index+'][username]'" x-model="employee.username" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                            placeholder="username_unik">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Email
                        </label>
                        <input type="email" :name="'employees['+index+'][email]'" x-model="employee.email"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                            placeholder="email@example.com">
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            No. Telepon
                        </label>
                        <input type="text" :name="'employees['+index+'][phone]'" x-model="employee.phone"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                            placeholder="08123456789">
                    </div>

                    <!-- Role -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Role/Jabatan <span class="text-red-500">*</span>
                        </label>
                        <select :name="'employees['+index+'][role]'" x-model="employee.role" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                            <option value="RT">RT (Rukun Tetangga)</option>
                            <option value="RW">RW (Rukun Warga)</option>
                            <option value="ADMIN">Admin Cluster</option>
                        </select>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" :name="'employees['+index+'][password]'" x-model="employee.password"
                            required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                            placeholder="Min. 8 karakter">
                    </div>
                </div>
            </div>
        </template>

        <!-- Add Employee Button -->
        <button type="button" @click="addEmployee"
            class="w-full px-4 py-3 border-2 border-dashed border-purple-300 text-purple-600 rounded-lg hover:bg-purple-50 transition font-semibold">
            <i class="fa-solid fa-user-plus mr-1"></i> Tambah Karyawan
        </button>

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex gap-3">
                <i class="fa-solid fa-info-circle text-blue-600 text-lg"></i>
                <div class="text-sm text-gray-700">
                    <p class="font-semibold mb-1">Informasi Penting:</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        <li>Setiap karyawan yang ditambahkan akan otomatis dibuatkan akun user</li>
                        <li>Username harus unik dan belum terdaftar di sistem</li>
                        <li>Karyawan dapat login menggunakan username dan password yang dibuat</li>
                        <li>Anda bisa skip langkah ini dan menambahkan karyawan nanti</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>