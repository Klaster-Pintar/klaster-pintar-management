@extends('layouts.app')

@section('title', 'Settings - Profile')

@section('content')
    <div class="flex min-h-screen bg-gradient-to-br from-gray-50 to-blue-50" x-data="{ sidebarOpen: false }">
        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <!-- Sidebar Component -->
        <x-admin.sidebar activeMenu="settings" />

        <!-- Main Wrapper -->
        <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">
            <!-- Header Component -->
            <x-admin.header />

            <!-- Main Content - Scrollable -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <div class="max-w-4xl mx-auto space-y-6">
                    <!-- Page Header -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                                <i class="fa-solid fa-user-gear text-blue-600 text-xl"></i>
                            </div>
                            <span>Profile Settings</span>
                        </h2>
                        <p class="text-gray-600 mt-2 ml-15">Kelola informasi profil dan keamanan akun Anda</p>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow flex items-center gap-3"
                            x-data="{ show: true }" x-show="show" x-transition>
                            <i class="fa-solid fa-circle-check text-green-500 text-xl"></i>
                            <div class="flex-1">
                                <p class="text-green-800 font-medium">{{ session('success') }}</p>
                            </div>
                            <button @click="show = false" class="text-green-500 hover:text-green-700">
                                <i class="fa-solid fa-xmark text-xl"></i>
                            </button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow" x-data="{ show: true }"
                            x-show="show" x-transition>
                            <div class="flex items-start gap-3">
                                <i class="fa-solid fa-circle-exclamation text-red-500 text-xl"></i>
                                <div class="flex-1">
                                    <p class="text-red-800 font-medium mb-2">Terdapat kesalahan pada input Anda:</p>
                                    <ul class="list-disc list-inside space-y-1 text-sm text-red-700">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <button @click="show = false" class="text-red-500 hover:text-red-700">
                                    <i class="fa-solid fa-xmark text-xl"></i>
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Profile Information Card -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6">
                            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                <i class="fa-solid fa-id-card"></i>
                                <span>Informasi Profil</span>
                            </h3>
                        </div>
                        <form action="{{ route('admin.settings.update') }}" method="POST"
                            enctype="multipart/form-data" class="p-6 space-y-6">
                            @csrf

                            <!-- Avatar Upload Section -->
                            <div class="flex flex-col md:flex-row items-center gap-6 p-6 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="relative group">
                                    @if (Auth::user()->avatar && file_exists(storage_path('app/public/' . Auth::user()->avatar)))
                                        <img id="avatar-preview" src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                            alt="Avatar"
                                            class="w-32 h-32 rounded-full object-cover border-4 border-blue-500 shadow-lg">
                                    @else
                                        <div id="avatar-preview"
                                            class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-4xl font-bold shadow-lg border-4 border-white">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div
                                        class="absolute inset-0 bg-black bg-opacity-50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <i class="fa-solid fa-camera text-white text-2xl"></i>
                                    </div>
                                </div>
                                <div class="flex-1 text-center md:text-left">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fa-solid fa-image mr-1"></i> Foto Profil
                                    </label>
                                    <p class="text-sm text-gray-500 mb-3">Format: JPG, PNG (Maks. 2MB)</p>
                                    <input type="file" name="avatar" id="avatar-input" accept="image/*"
                                        class="hidden">
                                    <button type="button" onclick="document.getElementById('avatar-input').click()"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-md">
                                        <i class="fa-solid fa-upload"></i>
                                        <span>Upload Foto</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Name Field -->
                            <div class="space-y-2">
                                <label for="name" class="block text-sm font-semibold text-gray-700">
                                    <i class="fa-solid fa-user mr-1 text-blue-600"></i> Nama Lengkap
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                @error('name')
                                    <p class="text-sm text-red-600 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>
                                        {{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div class="space-y-2">
                                <label for="email" class="block text-sm font-semibold text-gray-700">
                                    <i class="fa-solid fa-envelope mr-1 text-blue-600"></i> Email
                                </label>
                                <input type="email" id="email" name="email"
                                    value="{{ old('email', Auth::user()->email) }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                @error('email')
                                    <p class="text-sm text-red-600 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>
                                        {{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end pt-4 border-t border-gray-200">
                                <button type="submit"
                                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center gap-2 font-semibold">
                                    <i class="fa-solid fa-floppy-disk"></i>
                                    <span>Simpan Perubahan</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Change Password Card -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-green-600 to-emerald-700 p-6">
                            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                <i class="fa-solid fa-lock"></i>
                                <span>Ubah Password</span>
                            </h3>
                        </div>
                        <form action="{{ route('admin.settings.password') }}" method="POST" class="p-6 space-y-6">
                            @csrf

                            <!-- Current Password -->
                            <div class="space-y-2">
                                <label for="current_password" class="block text-sm font-semibold text-gray-700">
                                    <i class="fa-solid fa-key mr-1 text-green-600"></i> Password Saat Ini
                                </label>
                                <input type="password" id="current_password" name="current_password" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                                @error('current_password')
                                    <p class="text-sm text-red-600 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>
                                        {{ $message }}</p>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div class="space-y-2">
                                <label for="new_password" class="block text-sm font-semibold text-gray-700">
                                    <i class="fa-solid fa-lock mr-1 text-green-600"></i> Password Baru
                                </label>
                                <input type="password" id="new_password" name="new_password" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                                <p class="text-xs text-gray-500 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-info"></i>
                                    Minimal 8 karakter, kombinasi huruf, angka, dan simbol
                                </p>
                                @error('new_password')
                                    <p class="text-sm text-red-600 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>
                                        {{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="space-y-2">
                                <label for="new_password_confirmation" class="block text-sm font-semibold text-gray-700">
                                    <i class="fa-solid fa-shield-halved mr-1 text-green-600"></i> Konfirmasi Password Baru
                                </label>
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end pt-4 border-t border-gray-200">
                                <button type="submit"
                                    class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center gap-2 font-semibold">
                                    <i class="fa-solid fa-shield-halved"></i>
                                    <span>Update Password</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Security Information -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg shadow">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-info text-blue-600 text-xl mt-1"></i>
                            <div>
                                <h4 class="font-semibold text-blue-900 mb-2">Tips Keamanan:</h4>
                                <ul class="space-y-1 text-sm text-blue-800">
                                    <li><i class="fa-solid fa-check-circle text-blue-600 mr-2"></i>Gunakan password yang
                                        kuat dan unik</li>
                                    <li><i class="fa-solid fa-check-circle text-blue-600 mr-2"></i>Jangan bagikan password
                                        Anda kepada siapa pun</li>
                                    <li><i class="fa-solid fa-check-circle text-blue-600 mr-2"></i>Ubah password secara
                                        berkala (minimal 3 bulan sekali)</li>
                                    <li><i class="fa-solid fa-check-circle text-blue-600 mr-2"></i>Logout dari akun jika
                                        menggunakan komputer bersama</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer Component -->
            <x-admin.footer />
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Avatar preview functionality
        document.getElementById('avatar-input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatar-preview');
                    if (preview.tagName === 'IMG') {
                        preview.src = e.target.result;
                    } else {
                        // Replace div with img
                        const img = document.createElement('img');
                        img.id = 'avatar-preview';
                        img.src = e.target.result;
                        img.className =
                            'w-32 h-32 rounded-full object-cover border-4 border-blue-500 shadow-lg';
                        preview.parentNode.replaceChild(img, preview);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
