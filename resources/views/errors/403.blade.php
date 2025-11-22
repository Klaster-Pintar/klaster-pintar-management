@extends('layouts.guest')

@section('title', '403 - Akses Ditolak')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-red-50 p-6">
        <div class="max-w-2xl w-full">
            <div class="bg-white rounded-3xl shadow-2xl p-8 lg:p-12 text-center">
                <!-- Error Icon -->
                <div class="mb-6">
                    <div
                        class="inline-flex items-center justify-center w-32 h-32 rounded-full bg-gradient-to-br from-red-400 to-red-600 shadow-lg">
                        <i class="fa-solid fa-ban text-6xl text-white"></i>
                    </div>
                </div>

                <!-- 403 Text -->
                <h1 class="text-8xl lg:text-9xl font-bold mb-4">
                    <span class="bg-gradient-to-r from-blue-600 to-red-600 bg-clip-text text-transparent">403</span>
                </h1>

                <!-- Error Message -->
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-4">
                    Akses Ditolak
                </h2>

                <p class="text-gray-600 mb-8 text-lg">
                    Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.
                </p>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ route('admin.dashboard') }}"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-4 rounded-xl font-semibold hover:shadow-lg transform hover:-translate-y-1 transition duration-200">
                        <i class="fa-solid fa-home"></i>
                        Kembali ke Dashboard
                    </a>

                    <a href="javascript:history.back()"
                        class="inline-flex items-center gap-2 bg-white border-2 border-gray-300 text-gray-700 px-8 py-4 rounded-xl font-semibold hover:bg-gray-50 hover:border-gray-400 transition duration-200">
                        <i class="fa-solid fa-arrow-left"></i>
                        Halaman Sebelumnya
                    </a>
                </div>

                <!-- Help Text -->
                <div class="mt-12 pt-8 border-t border-gray-200">
                    <p class="text-sm text-gray-500">
                        Jika Anda merasa ini adalah kesalahan, silakan hubungi administrator sistem.
                    </p>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="mt-8 text-center text-gray-400 text-sm">
                <p>iManagement Smart Reservoir &copy; {{ date('Y') }}</p>
            </div>
        </div>
    </div>
@endsection