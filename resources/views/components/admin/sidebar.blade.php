<!-- Sidebar Component - Fixed -->
@props(['activeMenu' => 'dashboard'])

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="w-64 bg-white shadow-lg fixed inset-y-0 left-0 lg:translate-x-0 transition-transform duration-300 z-50"
    x-data="{
        activeParent: '{{ $activeMenu }}',
        openMenus: {
            master: false,
            finance: false,
            iot: false,
            affiliate: false
        },
        toggleMenu(menu) {
            this.openMenus[menu] = !this.openMenus[menu];
        },
        isMenuOpen(menu) {
            return this.openMenus[menu];
        }
    }">
    <div class="h-full flex flex-col">
        <!-- Logo - Clickable -->
        <div
            class="flex items-center gap-2 p-4 border-b border-gray-200 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 shadow-lg">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 w-full group">
                <img src="{{ asset('images/logo.png') }}" class="w-full transition-transform group-hover:scale-105"
                    alt="iManagement Logo" />
            </a>
            <!-- Close button for mobile -->
            <button @click="sidebarOpen = false"
                class="lg:hidden text-white text-2xl hover:text-red-300 transition-colors">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <!-- Navigation - Scrollable -->
        <nav class="flex-1 overflow-y-auto p-3 space-y-0.5 text-gray-700">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all {{ $activeMenu === 'dashboard' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md' : 'hover:bg-blue-50' }} group">
                <div
                    class="w-8 h-8 rounded-md flex items-center justify-center {{ $activeMenu === 'dashboard' ? 'bg-white bg-opacity-20' : 'bg-blue-100 group-hover:bg-blue-200' }} transition-colors">
                    <i
                        class="fa-solid fa-house text-sm {{ $activeMenu === 'dashboard' ? 'text-white' : 'text-blue-600' }}"></i>
                </div>
                <span class="text-sm font-medium">Dashboard</span>
            </a>

            <!-- Master -->
            <div
                x-data="{ open: {{ $activeMenu === 'master' || str_starts_with($activeMenu, 'master.') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-2.5 px-3 py-2 rounded-lg transition-all group {{ str_starts_with($activeMenu, 'master') ? 'bg-purple-50 text-purple-700' : 'hover:bg-gray-50' }}">
                    <span class="flex items-center gap-2.5">
                        <div
                            class="w-8 h-8 rounded-md flex items-center justify-center {{ str_starts_with($activeMenu, 'master') ? 'bg-purple-200' : 'bg-purple-100 group-hover:bg-purple-200' }} transition-colors">
                            <i
                                class="fa-solid fa-layer-group text-sm {{ str_starts_with($activeMenu, 'master') ? 'text-purple-700' : 'text-purple-600' }}"></i>
                        </div>
                        <span class="text-sm font-medium">Master</span>
                    </span>
                    <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"
                        class="fa-solid text-xs transition-transform"></i>
                </button>

                <div x-show="open" x-collapse class="mt-1 space-y-0.5 ml-11">
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-md transition-all text-xs {{ $activeMenu === 'master.user' ? 'bg-blue-500 text-white font-medium' : 'hover:bg-blue-50 text-gray-600' }}">
                        <i class="fa-solid fa-user-shield w-4"></i>
                        <span>Owner Management</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-md transition-all text-xs {{ $activeMenu === 'master.cluster' ? 'bg-blue-500 text-white font-medium' : 'hover:bg-blue-50 text-gray-600' }}">
                        <i class="fa-solid fa-warehouse w-4"></i>
                        <span>Cluster Management</span>
                    </a>
                </div>
            </div>

            <!-- Finance & Subscription -->
            <div x-data="{ open: {{ str_starts_with($activeMenu, 'finance') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-2.5 px-3 py-2 rounded-lg transition-all group {{ str_starts_with($activeMenu, 'finance') ? 'bg-green-50 text-green-700' : 'hover:bg-gray-50' }}">
                    <span class="flex items-center gap-2.5">
                        <div
                            class="w-8 h-8 rounded-md flex items-center justify-center {{ str_starts_with($activeMenu, 'finance') ? 'bg-green-200' : 'bg-green-100 group-hover:bg-green-200' }} transition-colors">
                            <i
                                class="fa-solid fa-wallet text-sm {{ str_starts_with($activeMenu, 'finance') ? 'text-green-700' : 'text-green-600' }}"></i>
                        </div>
                        <span class="text-sm font-medium">Finance</span>
                    </span>
                    <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"
                        class="fa-solid text-xs transition-transform"></i>
                </button>

                <div x-show="open" x-collapse class="mt-1 space-y-0.5 ml-11">
                    <a href="#"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-md transition-all text-xs {{ $activeMenu === 'finance.subscription' ? 'bg-green-500 text-white font-medium' : 'hover:bg-green-50 text-gray-600' }}">
                        <i class="fa-solid fa-receipt w-4"></i>
                        <span>Cluster Subscription</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-md transition-all text-xs {{ $activeMenu === 'finance.notification' ? 'bg-green-500 text-white font-medium' : 'hover:bg-green-50 text-gray-600' }}">
                        <i class="fa-solid fa-bell w-4"></i>
                        <span>Notification</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-md transition-all text-xs {{ $activeMenu === 'finance.reconsiliation' ? 'bg-green-500 text-white font-medium' : 'hover:bg-green-50 text-gray-600' }}">
                        <i class="fa-solid fa-scale-balanced w-4"></i>
                        <span>Reconsiliation</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-md transition-all text-xs {{ $activeMenu === 'finance.reports' ? 'bg-green-500 text-white font-medium' : 'hover:bg-green-50 text-gray-600' }}">
                        <i class="fa-solid fa-file-invoice-dollar w-4"></i>
                        <span>Finance Reports</span>
                    </a>
                </div>
            </div>

            <!-- IoT Management & Monitoring -->
            <div x-data="{ open: {{ str_starts_with($activeMenu, 'iot') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-2.5 px-3 py-2 rounded-lg transition-all group {{ str_starts_with($activeMenu, 'iot') ? 'bg-orange-50 text-orange-700' : 'hover:bg-gray-50' }}">
                    <span class="flex items-center gap-2.5">
                        <div
                            class="w-8 h-8 rounded-md flex items-center justify-center {{ str_starts_with($activeMenu, 'iot') ? 'bg-orange-200' : 'bg-orange-100 group-hover:bg-orange-200' }} transition-colors">
                            <i
                                class="fa-solid fa-microchip text-sm {{ str_starts_with($activeMenu, 'iot') ? 'text-orange-700' : 'text-orange-600' }}"></i>
                        </div>
                        <span class="text-sm font-medium">IoT Monitoring</span>
                    </span>
                    <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"
                        class="fa-solid text-xs transition-transform"></i>
                </button>

                <div x-show="open" x-collapse class="mt-1 space-y-0.5 ml-11">
                    <a href="#"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-md transition-all text-xs {{ $activeMenu === 'iot.devices' ? 'bg-orange-500 text-white font-medium' : 'hover:bg-orange-50 text-gray-600' }}">
                        <i class="fa-solid fa-satellite-dish w-4"></i>
                        <span>Device Tracking</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-md transition-all text-xs {{ $activeMenu === 'iot.logs' ? 'bg-orange-500 text-white font-medium' : 'hover:bg-orange-50 text-gray-600' }}">
                        <i class="fa-solid fa-file-lines w-4"></i>
                        <span>Log Data</span>
                    </a>
                </div>
            </div>

            <!-- Affiliate Management -->
            <div x-data="{ open: {{ str_starts_with($activeMenu, 'affiliate') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-2.5 px-3 py-2 rounded-lg transition-all group {{ str_starts_with($activeMenu, 'affiliate') ? 'bg-pink-50 text-pink-700' : 'hover:bg-gray-50' }}">
                    <span class="flex items-center gap-2.5">
                        <div
                            class="w-8 h-8 rounded-md flex items-center justify-center {{ str_starts_with($activeMenu, 'affiliate') ? 'bg-pink-200' : 'bg-pink-100 group-hover:bg-pink-200' }} transition-colors">
                            <i
                                class="fa-solid fa-handshake text-sm {{ str_starts_with($activeMenu, 'affiliate') ? 'text-pink-700' : 'text-pink-600' }}"></i>
                        </div>
                        <span class="text-sm font-medium">Affiliate</span>
                    </span>
                    <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"
                        class="fa-solid text-xs transition-transform"></i>
                </button>

                <div x-show="open" x-collapse class="mt-1 space-y-0.5 ml-11">
                    <a href="#"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-md transition-all text-xs {{ $activeMenu === 'affiliate.users' ? 'bg-pink-500 text-white font-medium' : 'hover:bg-pink-50 text-gray-600' }}">
                        <i class="fa-solid fa-users w-4"></i>
                        <span>Affiliate Users</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-md transition-all text-xs {{ $activeMenu === 'affiliate.reports' ? 'bg-pink-500 text-white font-medium' : 'hover:bg-pink-50 text-gray-600' }}">
                        <i class="fa-solid fa-chart-simple w-4"></i>
                        <span>Affiliate Reports</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-md transition-all text-xs {{ $activeMenu === 'affiliate.commissions' ? 'bg-pink-500 text-white font-medium' : 'hover:bg-pink-50 text-gray-600' }}">
                        <i class="fa-solid fa-coins w-4"></i>
                        <span>Commissions</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-md transition-all text-xs {{ $activeMenu === 'affiliate.leads' ? 'bg-pink-500 text-white font-medium' : 'hover:bg-pink-50 text-gray-600' }}">
                        <i class="fa-solid fa-file-contract w-4"></i>
                        <span>Lead Tracking</span>
                    </a>
                </div>
            </div>
        </nav>

        <!-- Footer/Version -->
        <div class="p-3 border-t border-gray-200 bg-gradient-to-r from-gray-50 via-blue-50 to-indigo-50">
            <p class="text-[10px] text-gray-500 text-center font-medium">
                <i class="fa-solid fa-code mr-1 text-blue-600"></i>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">iManagement
                    v1.0.0</span>
            </p>
        </div>
    </div>
</aside>