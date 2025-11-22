@extends('layouts.app')

@section('title', 'Settings - Profile')

@section('content')
    <div class="flex min-h-screen bg-gray-50" x-data="{ sidebarOpen: false }">
        <!-- Sidebar - Fixed -->
        <aside class="w-64 bg-white shadow-lg fixed inset-y-0 left-0 lg:translate-x-0 transition-transform duration-300 z-50"
            :class="{ '-translate-x-full': !sidebarOpen }">
            <div class="h-full flex flex-col">
                <div class="flex items-center gap-2 p-4 border-b border-gray-200">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 w-full">
                        <img src="{{ asset('images/logo.png') }}" class="w-full" alt="Logo" />
                    </a>
                </div>
                <nav class="flex-1 overflow-y-auto p-4 space-y-2 text-gray-700">
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition">
                        <i class="fa-solid fa-house w-5"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.settings') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg bg-blue-50 text-blue-600 transition">
                        <i class="fa-solid fa-gear w-5"></i>
                        <span>Settings</span>
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">
            <!-- Header - Sticky -->
            <header class="bg-white shadow-sm sticky top-0 z-30">
                <div class="flex items-center justify-between px-4 lg:px-6 py-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-600 hover:text-gray-900">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>

                    <h1 class="text-xl lg:text-2xl font-bold text-gray-800 hidden lg:block">
                        <i class="fa-solid fa-gear text-blue-600"></i> Profile Settings
                    </h1>

                    <div class="flex items-center gap-4">
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                                @if (Auth::user()->avatar && file_exists(storage_path('app/public/' . Auth::user()->avatar)))
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar"
                                        class="w-10 h-10 rounded-full object-cover border-2 border-blue-500">
                                @else
                                    <div
                                        class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="text-left hidden lg:block">
                                    <p class="font-semibold text-gray-800 text-sm">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->role }}</p>
                                </div>
                                <i class="fa-solid fa-chevron-down text-gray-500 text-xs"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50"
                                style="display: none;">
                                <a href="{{ route('admin.settings') }}"
                                    class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fa-solid fa-gear mr-2"></i> Settings
                                </a>
                                <hr class="my-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                        <i class="fa-solid fa-right-from-bracket mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content - Scrollable -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6 space-y-6">
                <!-- Success Message -->
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative"
                        role="alert" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-check"></i>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Profile Card -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <div class="text-center">
                                <!-- Avatar Upload Form -->
                                <form method="POST" action="{{ route('admin.settings.avatar') }}"
                                    enctype="multipart/form-data" x-data="{ uploading: false }">
                                    @csrf
                                    <div class="relative inline-block">
                                        @if ($user->avatar && file_exists(storage_path('app/public/' . $user->avatar)))
                                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                                                class="w-32 h-32 rounded-full object-cover border-4 border-blue-500 mx-auto">
                                        @else
                                            <div
                                                class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-bold text-4xl mx-auto">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif

                                        <!-- Upload Button -->
                                        <label for="avatar-upload"
                                            class="absolute bottom-0 right-0 bg-blue-600 hover:bg-blue-700 text-white rounded-full p-2 cursor-pointer shadow-lg transition">
                                            <i class="fa-solid fa-camera"></i>
                                            <input type="file" id="avatar-upload" name="avatar" accept="image/*"
                                                class="hidden" @change="uploading = true; $el.form.submit()">
                                        </label>
                                    </div>

                                    @error('avatar')
                                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                    @enderror

                                    <div x-show="uploading" class="mt-2 text-blue-600 text-sm">
                                        <i class="fa-solid fa-spinner fa-spin"></i> Uploading...
                                    </div>
                                </form>

                                <h3 class="text-xl font-bold text-gray-800 mt-4">{{ $user->name }}</h3>
                                <p class="text-gray-600">{{ $user->email }}</p>
                                <span
                                    class="inline-block mt-2 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                                    {{ $user->role }}
                                </span>

                                <div class="mt-6 pt-6 border-t border-gray-200 space-y-3 text-left text-sm">
                                    <div class="flex items-center gap-2 text-gray-700">
                                        <i class="fa-solid fa-phone w-5 text-blue-600"></i>
                                        <span>{{ $user->phone ?? '-' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-700">
                                        <i class="fa-solid fa-venus-mars w-5 text-blue-600"></i>
                                        <span>{{ $user->gender == 'M' ? 'Laki-laki' : ($user->gender == 'F' ? 'Perempuan' : '-') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-700">
                                        <i class="fa-solid fa-calendar w-5 text-blue-600"></i>
                                        <span>{{ $user->date_birth ? date('d M Y', strtotime($user->date_birth)) : '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Information & Password Forms -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Profile Information Form -->
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                                <i class="fa-solid fa-user text-blue-600"></i>
                                Informasi Profile
                            </h3>

                            <form method="POST" action="{{ route('admin.settings.update') }}">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Name -->
                                    <div class="md:col-span-2">
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nama Lengkap <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="name" name="name"
                                            value="{{ old('name', $user->name) }}" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                                        @error('name')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="md:col-span-2">
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                            Email <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" id="email" name="email"
                                            value="{{ old('email', $user->email) }}" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                                        @error('email')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Phone -->
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nomor Telepon
                                        </label>
                                        <input type="text" id="phone" name="phone"
                                            value="{{ old('phone', $user->phone) }}"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror">
                                        @error('phone')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Gender -->
                                    <div>
                                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                            Jenis Kelamin
                                        </label>
                                        <select id="gender" name="gender"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('gender') border-red-500 @enderror">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="M" {{ old('gender', $user->gender) == 'M' ? 'selected' : '' }}>
                                                Laki-laki</option>
                                            <option value="F" {{ old('gender', $user->gender) == 'F' ? 'selected' : '' }}>
                                                Perempuan</option>
                                        </select>
                                        @error('gender')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Date of Birth -->
                                    <div class="md:col-span-2">
                                        <label for="date_birth" class="block text-sm font-medium text-gray-700 mb-2">
                                            Tanggal Lahir
                                        </label>
                                        <input type="date" id="date_birth" name="date_birth"
                                            value="{{ old('date_birth', $user->date_birth) }}"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('date_birth') border-red-500 @enderror">
                                        @error('date_birth')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Address -->
                                    <div class="md:col-span-2">
                                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                            Alamat
                                        </label>
                                        <textarea id="address" name="address" rows="3"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror">{{ old('address', $user->address) }}</textarea>
                                        @error('address')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="flex justify-end mt-6">
                                    <button type="submit"
                                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition shadow-md hover:shadow-lg">
                                        <i class="fa-solid fa-save mr-2"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Change Password Form -->
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                                <i class="fa-solid fa-lock text-blue-600"></i>
                                Ubah Password
                            </h3>

                            <form method="POST" action="{{ route('admin.settings.password') }}">
                                @csrf
                                <div class="space-y-4">
                                    <!-- Current Password -->
                                    <div>
                                        <label for="current_password"
                                            class="block text-sm font-medium text-gray-700 mb-2">
                                            Password Saat Ini <span class="text-red-500">*</span>
                                        </label>
                                        <input type="password" id="current_password" name="current_password" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('current_password') border-red-500 @enderror">
                                        @error('current_password')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- New Password -->
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                            Password Baru <span class="text-red-500">*</span>
                                        </label>
                                        <input type="password" id="password" name="password" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror">
                                        @error('password')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                        <p class="text-gray-500 text-xs mt-1">Minimal 8 karakter</p>
                                    </div>

                                    <!-- Confirm Password -->
                                    <div>
                                        <label for="password_confirmation"
                                            class="block text-sm font-medium text-gray-700 mb-2">
                                            Konfirmasi Password Baru <span class="text-red-500">*</span>
                                        </label>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>

                                <div class="flex justify-end mt-6">
                                    <button type="submit"
                                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition shadow-md hover:shadow-lg">
                                        <i class="fa-solid fa-key mr-2"></i> Ubah Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection
