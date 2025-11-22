@extends('layouts.app')

@section('title', 'Edit Owner - iManagement')

@section('content')
    <div class="flex min-h-screen bg-gradient-to-br from-gray-50 to-blue-50" x-data="{ sidebarOpen: false }">
        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
            x-transition></div>

        <!-- Sidebar Component -->
        <x-admin.sidebar activeMenu="master.user" />

        <!-- Main Wrapper -->
        <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">
            <!-- Header Component -->
            <x-admin.header />

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <div class="w-full space-y-4">
                    <!-- Page Header -->
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.users.index') }}"
                            class="p-2 bg-white rounded-lg shadow hover:shadow-lg transition flex-shrink-0">
                            <i class="fa-solid fa-arrow-left text-gray-600"></i>
                        </a>
                        <div class="bg-white rounded-xl shadow-lg p-4 lg:p-6 flex-1">
                            <h2 class="text-xl lg:text-2xl font-bold text-gray-800 flex items-center gap-3">
                                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-user-pen text-purple-600 text-lg lg:text-xl"></i>
                                </div>
                                <span class="truncate">Edit Owner</span>
                            </h2>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow">
                            <div class="flex items-start gap-3">
                                <i class="fa-solid fa-circle-exclamation text-red-500 text-xl"></i>
                                <div class="flex-1">
                                    <p class="text-red-800 font-medium mb-2">Terdapat kesalahan pada input:</p>
                                    <ul class="list-disc list-inside space-y-1 text-sm text-red-700">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        <!-- Personal Information Card -->
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                    <i class="fa-solid fa-id-card"></i>
                                    <span>Informasi Personal</span>
                                </h3>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Avatar Upload -->
                                <div class="flex flex-col md:flex-row items-center gap-6 p-6 bg-gray-50 rounded-xl border border-gray-200">
                                    <div class="relative group" x-data="{ preview: null }">
                                        <div x-show="!preview"
                                            class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-4xl font-bold shadow-lg border-4 border-white">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                        <img x-show="preview" :src="preview" alt="Preview"
                                            class="w-32 h-32 rounded-full object-cover border-4 border-blue-500 shadow-lg"
                                            style="display: none;">
                                        <div
                                            class="absolute inset-0 bg-black bg-opacity-50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <i class="fa-solid fa-camera text-white text-2xl"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1" x-data="{
                                        handleFileChange(event) {
                                            const file = event.target.files[0];
                                            if (file) {
                                                const reader = new FileReader();
                                                reader.onload = (e) => {
                                                    this.$el.closest('[x-data]').querySelector('[x-data]').__x.$data.preview = e.target.result;
                                                };
                                                reader.readAsDataURL(file);
                                            }
                                        }
                                    }">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fa-solid fa-image mr-1"></i> Foto Profil
                                        </label>
                                        <p class="text-sm text-gray-500 mb-3">Format: JPG, PNG (Maks. 2MB)</p>
                                        <input type="file" name="avatar" id="avatar-input" accept="image/*"
                                            @change="handleFileChange" class="hidden">
                                        <button type="button" onclick="document.getElementById('avatar-input').click()"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-md">
                                            <i class="fa-solid fa-upload"></i>
                                            <span>Upload Foto</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Full Name -->
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fa-solid fa-user mr-1 text-blue-600"></i> Nama Lengkap <span
                                                class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="name" value="{{ old('name') }}" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                    </div>

                                    <!-- Username -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fa-solid fa-at mr-1 text-blue-600"></i> Username <span
                                                class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="username" value="{{ old('username') }}" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fa-solid fa-envelope mr-1 text-blue-600"></i> Email
                                        </label>
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                    </div>

                                    <!-- Phone -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fa-solid fa-phone mr-1 text-blue-600"></i> No. Telepon
                                        </label>
                                        <input type="text" name="phone" value="{{ old('phone') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                    </div>

                                    <!-- Gender -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fa-solid fa-venus-mars mr-1 text-blue-600"></i> Jenis Kelamin
                                        </label>
                                        <select name="gender"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            @foreach ($genders as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ old('gender') == $key ? 'selected' : '' }}>{{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Date of Birth -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fa-solid fa-calendar mr-1 text-blue-600"></i> Tanggal Lahir
                                        </label>
                                        <input type="date" name="date_birth" value="{{ old('date_birth') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                    </div>

                                    <!-- Address -->
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fa-solid fa-location-dot mr-1 text-blue-600"></i> Alamat
                                        </label>
                                        <textarea name="address" rows="3"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">{{ old('address') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Settings Card -->
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                            <div class="bg-gradient-to-r from-green-600 to-emerald-700 p-6">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                    <i class="fa-solid fa-user-gear"></i>
                                    <span>Pengaturan Akun</span>
                                </h3>
                            </div>
                            <div class="p-6 space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Role -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fa-solid fa-user-tag mr-1 text-green-600"></i> Role <span
                                                class="text-red-500">*</span>
                                        </label>
                                        <select name="role" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                                            <option value="">Pilih Role</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role }}"
                                                    {{ old('role') == $role ? 'selected' : '' }}>{{ $role }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Status -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fa-solid fa-circle-check mr-1 text-green-600"></i> Status <span
                                                class="text-red-500">*</span>
                                        </label>
                                        <select name="status" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                                            @foreach ($statuses as $status)
                                                <option value="{{ $status }}"
                                                    {{ old('status', 'PENDING') == $status ? 'selected' : '' }}>
                                                    {{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Password -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fa-solid fa-lock mr-1 text-green-600"></i> Password <span
                                                class="text-red-500">*</span>
                                        </label>
                                        <input type="password" name="password" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                                        <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                                    </div>

                                    <!-- Password Confirmation -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fa-solid fa-shield-halved mr-1 text-green-600"></i> Konfirmasi
                                            Password <span class="text-red-500">*</span>
                                        </label>
                                        <input type="password" name="password_confirmation" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                                    </div>
                                </div>

                                <!-- Active Flag -->
                                <div class="flex items-center gap-3 p-4 bg-green-50 rounded-lg border border-green-200">
                                    <input type="checkbox" name="active_flag" id="active_flag" value="1"
                                        {{ old('active_flag', true) ? 'checked' : '' }}
                                        class="w-5 h-5 text-green-600 rounded focus:ring-2 focus:ring-green-500">
                                    <label for="active_flag" class="text-sm font-medium text-gray-700">
                                        <i class="fa-solid fa-toggle-on text-green-600 mr-1"></i> Aktifkan user setelah
                                        dibuat
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 justify-end">
                            <a href="{{ route('admin.users.index') }}"
                                class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                                <i class="fa-solid fa-xmark mr-1"></i> Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:shadow-xl transition transform hover:-translate-y-1 font-semibold">
                                <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan User
                            </button>
                        </div>
                    </form>
                </div>
            </main>

            <!-- Footer Component -->
            <x-admin.footer />
        </div>
    </div>
@endsection
