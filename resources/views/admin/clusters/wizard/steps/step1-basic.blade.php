<div>
    <div class="mb-6">
        <h3 class="text-xl lg:text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-info-circle text-blue-600"></i>
            <span>Informasi Dasar Cluster</span>
        </h3>
        <p class="text-sm text-gray-600 mt-1">Lengkapi data utama cluster Anda</p>
    </div>

    <div class="space-y-6">
        <!-- Logo & Picture Upload -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Logo -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-image text-blue-600 mr-1"></i> Logo Cluster
                </label>
                <div class="flex items-center gap-4">
                    <div x-data="{ preview: null }" class="flex-1">
                        <div
                            class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-500 transition">
                            <div x-show="!preview" class="space-y-2">
                                <i class="fa-solid fa-cloud-arrow-up text-4xl text-gray-400"></i>
                                <p class="text-sm text-gray-600">Upload Logo (PNG/JPG)</p>
                            </div>
                            <img x-show="preview" :src="preview" class="mx-auto h-32 object-contain"
                                style="display: none;">
                            <input type="file" name="logo" accept="image/*" class="hidden" id="logo-input"
                                @change="preview = URL.createObjectURL($event.target.files[0])">
                            <button type="button" @click="document.getElementById('logo-input').click()"
                                class="mt-2 px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                                Pilih File
                            </button>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-1">Ukuran maksimal 2MB</p>
            </div>

            <!-- Picture -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-image text-blue-600 mr-1"></i> Foto/Banner Cluster
                </label>
                <div class="flex items-center gap-4">
                    <div x-data="{ preview: null }" class="flex-1">
                        <div
                            class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-500 transition">
                            <div x-show="!preview" class="space-y-2">
                                <i class="fa-solid fa-cloud-arrow-up text-4xl text-gray-400"></i>
                                <p class="text-sm text-gray-600">Upload Foto (PNG/JPG)</p>
                            </div>
                            <img x-show="preview" :src="preview" class="mx-auto h-32 object-cover rounded"
                                style="display: none;">
                            <input type="file" name="picture" accept="image/*" class="hidden" id="picture-input"
                                @change="preview = URL.createObjectURL($event.target.files[0])">
                            <button type="button" @click="document.getElementById('picture-input').click()"
                                class="mt-2 px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                                Pilih File
                            </button>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-1">Ukuran maksimal 2MB</p>
            </div>
        </div>

        <!-- Name -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fa-solid fa-building text-blue-600 mr-1"></i> Nama Cluster <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" x-model="formData.name" required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                placeholder="Contoh: Cluster Emerald Garden">
        </div>

        <!-- Description -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fa-solid fa-align-left text-blue-600 mr-1"></i> Deskripsi Cluster
            </label>
            <textarea name="description" x-model="formData.description" rows="4"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                placeholder="Deskripsikan cluster Anda..."></textarea>
        </div>

        <!-- Contact Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Phone -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-phone text-blue-600 mr-1"></i> No. Telepon <span class="text-red-500">*</span>
                </label>
                <input type="text" name="phone" x-model="formData.phone" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="08123456789">
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-envelope text-blue-600 mr-1"></i> Email <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" x-model="formData.email" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="cluster@example.com">
            </div>
        </div>

        <!-- Radius Settings -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h4 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-circle-dot text-blue-600"></i>
                Pengaturan Radius (dalam meter)
            </h4>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Radius Check-in -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Radius Check-in
                    </label>
                    <input type="number" name="radius_checkin" x-model="formData.radius_checkin" min="1" max="100"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <p class="text-xs text-gray-600 mt-1">Jarak maksimal untuk check-in (default: 5 meter)</p>
                </div>

                <!-- Radius Patrol -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Radius Patrol
                    </label>
                    <input type="number" name="radius_patrol" x-model="formData.radius_patrol" min="1" max="100"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <p class="text-xs text-gray-600 mt-1">Jarak maksimal untuk patroli (default: 5 meter)</p>
                </div>
            </div>
        </div>
    </div>
</div>