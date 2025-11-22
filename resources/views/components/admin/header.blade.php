<!-- Header Component - Sticky -->
<header class="bg-white shadow-sm sticky top-0 z-30">
    <div class="flex justify-between items-center p-4 lg:px-8">
        <!-- Mobile Menu Button -->
        <button @click="sidebarOpen = true" class="lg:hidden text-2xl text-gray-700 hover:text-blue-600 transition">
            <i class="fa-solid fa-bars"></i>
        </button>

        <!-- Date & Time -->
        <h2 class="text-sm lg:text-lg font-semibold text-gray-700 flex items-center gap-2">
            <i class="fa-regular fa-calendar text-blue-600"></i>
            <span
                class="hidden sm:inline">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, DD-MM-YYYY') }}</span>
            <span class="sm:hidden">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</span>
            <i class="fa-regular fa-clock text-blue-600 ml-2"></i>
            <span>{{ \Carbon\Carbon::now()->format('H:i') }}</span>
        </h2>

        <!-- Right Icons & User Dropdown -->
        <div class="flex items-center gap-3 lg:gap-4">
            <!-- Notification Bell -->
            <div class="relative">
                <button class="text-lg lg:text-xl text-gray-600 hover:text-blue-600 transition relative">
                    <i class="fa-solid fa-bell"></i>
                    <span
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center font-bold">3</span>
                </button>
            </div>

            <!-- Settings -->
            <a href="{{ route('admin.settings') }}"
                class="text-lg lg:text-xl text-gray-600 hover:text-blue-600 transition">
                <i class="fa-solid fa-gear"></i>
            </a>

            <!-- User Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="hidden lg:flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-full cursor-pointer hover:shadow-lg transition">
                    @if (Auth::user()->avatar && file_exists(storage_path('app/public/' . Auth::user()->avatar)))
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar"
                            class="w-8 h-8 rounded-full object-cover border-2 border-white">
                    @else
                        <i class="fa-solid fa-user-circle text-xl"></i>
                    @endif
                    <span class="font-medium text-sm">{{ Auth::user()->name }}</span>
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </button>

                <!-- Mobile Avatar Button -->
                <button @click="open = !open" class="lg:hidden">
                    @if (Auth::user()->avatar && file_exists(storage_path('app/public/' . Auth::user()->avatar)))
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar"
                            class="w-10 h-10 rounded-full object-cover border-2 border-blue-600">
                    @else
                        <div
                            class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50" style="display: none;">
                    <div class="px-4 py-2 border-b border-gray-100">
                        <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                    <a href="{{ route('admin.settings') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                        <i class="fa-solid fa-gear mr-2 text-blue-600"></i> Settings
                    </a>
                    <hr class="my-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                            <i class="fa-solid fa-right-from-bracket mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>