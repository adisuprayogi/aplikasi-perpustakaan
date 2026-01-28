<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OPAC') - Perpustakaan</title>
    <link rel="stylesheet" href="{{ asset('dist/css/main.css') }}">
    <script defer src="{{ asset('dist/js/main.js') }}"></script>
</head>
<body class="bg-gray-50 min-h-screen" x-data="{ mobileMenuOpen: false, searchOpen: false }">
    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-lg shadow-sm sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ route('opac.index') }}" class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Perpustakaan</span>
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('opac.index') }}" class="px-4 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('opac.index') ? 'text-white bg-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">Beranda</a>
                    <a href="{{ route('opac.search') }}" class="px-4 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('opac.search') ? 'text-white bg-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">Cari</a>
                    <a href="{{ route('opac.advanced') }}" class="px-4 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('opac.advanced') ? 'text-white bg-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">Pencarian Lanjutan</a>
                </nav>

                <!-- Right Side -->
                <div class="flex items-center space-x-3">
                    @auth
                    <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Dashboard
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Login
                    </a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = true" class="md:hidden p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden border-t border-gray-100 bg-white"
             style="display: none;">
            <div class="px-4 py-3 space-y-1">
                <a href="{{ route('opac.index') }}" class="block px-4 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('opac.index') ? 'text-white bg-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">Beranda</a>
                <a href="{{ route('opac.search') }}" class="block px-4 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('opac.search') ? 'text-white bg-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">Cari</a>
                <a href="{{ route('opac.advanced') }}" class="block px-4 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('opac.advanced') ? 'text-white bg-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">Pencarian Lanjutan</a>
                @auth
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium text-center">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="block px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium text-center">Login</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <span class="text-lg font-bold">Perpustakaan</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Sistem Perpustakaan Digital Kampus. Memberikan akses ke koleksi untuk mendukung pembelajaran dan penelitian.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-sm font-semibold mb-4 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                        Navigasi
                    </h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('opac.index') }}" class="text-gray-400 hover:text-white transition">Beranda</a></li>
                        <li><a href="{{ route('opac.search') }}" class="text-gray-400 hover:text-white transition">Cari Koleksi</a></li>
                        <li><a href="{{ route('opac.advanced') }}" class="text-gray-400 hover:text-white transition">Pencarian Lanjutan</a></li>
                    </ul>
                </div>

                <!-- Resources -->
                <div>
                    <h3 class="text-sm font-semibold mb-4 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Koleksi
                    </h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('opac.search') }}?collection_type=1" class="text-gray-400 hover:text-white transition">Buku</a></li>
                        <li><a href="{{ route('opac.search') }}?collection_type=2" class="text-gray-400 hover:text-white transition">Jurnal</a></li>
                        <li><a href="{{ route('opac.search') }}?collection_type=3" class="text-gray-400 hover:text-white transition">Skripsi/Tesis</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-sm font-semibold mb-4 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Hubungi Kami
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Gedung Perpustakaan Lt. 1
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            library@campus.ac.id
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            (021) 1234-5678
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-gray-800 mt-10 pt-8 flex flex-col sm:flex-row items-center justify-between">
                <p class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} Perpustakaan Kampus. All rights reserved.
                </p>
                <div class="flex items-center space-x-4 mt-4 sm:mt-0">
                    <a href="#" class="text-gray-500 hover:text-white text-sm transition">Privacy</a>
                    <a href="#" class="text-gray-500 hover:text-white text-sm transition">Terms</a>
                    <a href="#" class="text-gray-500 hover:text-white text-sm transition">Help</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
