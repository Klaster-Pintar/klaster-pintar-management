@extends('layouts.guest')

@section('title', '404 - Halaman Tidak Ditemukan')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-orange-50 p-6">
        <div class="max-w-2xl w-full">
            <div class="bg-white rounded-3xl shadow-2xl p-8 lg:p-12 text-center">
                <!-- Error Icon -->
                <div class="mb-6">
                    <div
                        class="inline-flex items-center justify-center w-32 h-32 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 shadow-lg">
                        <i class="fa-solid fa-face-frown text-6xl text-white"></i>
                    </div>
                </div>

                <!-- 404 Text -->
                <h1 class="text-8xl lg:text-9xl font-bold mb-4">
                    <span class="bg-gradient-to-r from-blue-600 to-orange-600 bg-clip-text text-transparent">404</span>
                </h1>

                <!-- Error Message -->
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-4">
                    Halaman Tidak Ditemukan
                </h2>

                <p class="text-gray-600 mb-8 text-lg">
                    Maaf, halaman yang Anda cari tidak dapat ditemukan atau telah dipindahkan.
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
                    <p class="text-sm text-gray-500 mb-4">
                        Jika masalah berlanjut, silakan hubungi administrator sistem.
                    </p>
                    <div class="flex items-center justify-center gap-6 text-gray-400">
                        <a href="#" class="hover:text-blue-600 transition">
                            <i class="fa-solid fa-envelope text-xl"></i>
                        </a>
                        <a href="#" class="hover:text-blue-600 transition">
                            <i class="fa-solid fa-headset text-xl"></i>
                        </a>
                        <a href="#" class="hover:text-blue-600 transition">
                            <i class="fa-solid fa-question-circle text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="mt-8 text-center text-gray-400 text-sm">
                <p>iManagement Smart Reservoir &copy; {{ date('Y') }}</p>
            </div>
        </div>

        <!-- Background Decorative Elements -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
            <div
                class="absolute top-20 left-10 w-72 h-72 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob">
            </div>
            <div
                class="absolute top-40 right-10 w-72 h-72 bg-orange-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute -bottom-8 left-20 w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000">
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            @keyframes blob {

                0%,
                100% {
                    transform: translate(0px, 0px) scale(1);
                }

                33% {
                    transform: translate(30px, -50px) scale(1.1);
                }

                66% {
                    transform: translate(-20px, 20px) scale(0.9);
                }
            }

            .animate-blob {
                animation: blob 7s infinite;
            }

            .animation-delay-2000 {
                animation-delay: 2s;
            }

            .animation-delay-4000 {
                animation-delay: 4s;
            }
        </style>
    @endpush
@endsection