@extends('layouts.guest')

@section('title', 'Login - iManagement')

@section('content')
    <div class="flex flex-col lg:flex-row min-h-screen">
        <!-- Left Card Login -->
        <div class="w-full lg:w-1/2 flex justify-center items-center p-6">
            <div class="w-full max-w-md bg-white shadow-lg rounded-3xl p-10 border">
                <!-- Logo -->
                <div class="flex items-center justify-center mb-6">
                    <img src="{{ asset('images/logo-admin.png') }}" class="w-80 mr-2" alt="Logo Admin" />
                </div>

                <h2 class="text-2xl font-semibold text-center mb-6">
                    <span class="text-blue-700">Masuk</span> ke Backoffice
                </h2>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('login.post') }}" x-data="{ showPassword: false }">
                    @csrf

                    <label class="font-medium">Email atau Username</label>
                    <input type="text" name="email" value="{{ old('email') }}" placeholder="example@imanagement.com"
                        class="w-full mt-1 mb-4 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-600 focus:outline-none @error('email') border-red-500 @enderror"
                        required autofocus />

                    <label class="font-medium">Password</label>
                    <div class="relative mt-1 mb-2">
                        <input :type="showPassword ? 'text' : 'password'" name="password" placeholder="Password"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-600 focus:outline-none @error('password') border-red-500 @enderror"
                            required />
                        <i @click="showPassword = !showPassword" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"
                            class="fa-solid text-gray-500 absolute right-4 top-3 cursor-pointer"></i>
                    </div>

                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember"
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600">Ingat Saya</span>
                        </label>
                        <a href="#" class="text-blue-700 font-medium text-sm">Lupa Password?</a>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-800 hover:bg-blue-900 text-white font-semibold py-3 rounded-lg transition duration-200">
                        Masuk
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Background Section -->
        <div class="w-full lg:w-1/2 hidden lg:flex flex-col items-center justify-center relative overflow-hidden p-12">
            <!-- Gradient BG -->
            <div class="absolute inset-0 bg-gradient-to-r from-orange-500 to-orange-700"></div>

            <!-- Icon di Pojok Kanan Atas -->
            <div class="absolute top-6 right-6 z-20">
                <i class="fa-solid fa-droplet text-white text-4xl opacity-80"></i>
            </div>

            <!-- Content Container -->
            <div class="relative z-10 w-full max-w-lg">
                <!-- Logo Image (Di Atas) -->
                <div class="mb-8 flex justify-start">
                    <img src="{{ asset('images/logo-background.png') }}" class="w-48 lg:w-64 xl:w-72 h-auto"
                        alt="Logo Background" />
                </div>

                <!-- Text Content (Di Bawah - Rata Kiri) -->
                <div class="text-white">
                    <!-- Main Title -->
                    <h1 class="text-3xl lg:text-4xl xl:text-5xl font-bold mb-3 leading-tight">
                        IMANAGEMENT
                    </h1>

                    <!-- Subtitle -->
                    <h2 class="text-xl lg:text-2xl xl:text-3xl font-semibold mb-6">
                        Smart Reservoir
                    </h2>

                    <!-- Description -->
                    <p class="text-sm lg:text-base leading-relaxed opacity-90">
                        Sistem manajemen cerdas untuk monitoring dan pengelolaan
                        reservoir secara real-time
                    </p>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute bottom-0 left-0 w-40 h-40 bg-white opacity-5 rounded-full -mb-20 -ml-20"></div>
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -mt-16 -mr-16"></div>
        </div>
    </div>
@endsection