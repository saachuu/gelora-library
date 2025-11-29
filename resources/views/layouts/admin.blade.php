<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Dasbor</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 h-full">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-100 overflow-hidden">
        <!-- Sidebar -->
        <aside class="flex-shrink-0 hidden w-64 bg-white border-r md:block">
            <div class="flex flex-col h-full">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-center h-16 border-b px-4">
                     <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/logo.png') }}" alt="Gelora Library" class="h-10 w-auto">
                    </a>
                </div>
                <!-- Sidebar Links -->
                <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 rounded-md hover:bg-gray-200 {{ request()->routeIs('dashboard') ? 'bg-gray-200' : '' }}">
                        Dasbor
                    </a>
                    <a href="{{ route('dasbor.buku.index') }}" class="flex items-center px-6 py-3 text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors duration-200 {{ request()->routeIs('dasbor.buku.*') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="font-medium">Buku</span>
                </a>

                <a href="{{ route('dasbor.kategori.index') }}" class="flex items-center px-6 py-3 text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors duration-200 {{ request()->routeIs('dasbor.kategori.*') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span class="font-medium">Kategori</span>
                </a>
                    <a href="{{ route('dasbor.anggota.index') }}" class="flex items-center px-4 py-2 text-gray-700 rounded-md hover:bg-gray-200 {{ request()->routeIs('dasbor.anggota.*') ? 'bg-gray-200' : '' }}">
                        Manajemen Anggota
                    </a>
                    <a href="{{ route('dasbor.sirkulasi.index') }}" class="flex items-center px-4 py-2 text-gray-700 rounded-md hover:bg-gray-200 {{ request()->routeIs('dasbor.sirkulasi.*') ? 'bg-gray-200' : '' }}">
                        Sirkulasi
                    </a>
                    <a href="{{ route('dasbor.laporan.index') }}" class="flex items-center px-4 py-2 text-gray-700 rounded-md hover:bg-gray-200 {{ request()->routeIs('dasbor.laporan.*') ? 'bg-gray-200' : '' }}">
                        Laporan
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Mobile Sidebar -->
        <div x-show="sidebarOpen" class="fixed inset-0 z-40 flex md:hidden" role="dialog" aria-modal="true">
            <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-600 bg-opacity-75" aria-hidden="true" @click="sidebarOpen = false"></div>

            <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
                <div class="absolute top-0 right-0 pt-2 pr-2">
                    <button type="button" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" @click="sidebarOpen = false">
                        <span class="sr-only">Close sidebar</span>
                        <svg class="h-6 w-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                    <div class="flex-shrink-0 flex items-center px-4">
                         <a href="{{ route('dashboard') }}">
                            <img src="{{ asset('images/logo.png') }}" alt="Gelora Library" class="h-10 w-auto">
                        </a>
                    </div>
                    <nav class="mt-5 px-2 space-y-1">
                        <a href="{{ route('dashboard') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-900 hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : '' }}">
                            Dasbor
                        </a>
                        <a href="{{ route('dasbor.buku.index') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('dasbor.buku.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                            Manajemen Buku
                        </a>
                        <a href="{{ route('dasbor.anggota.index') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('dasbor.anggota.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                            Manajemen Anggota
                        </a>
                        <a href="{{ route('dasbor.kategori.index') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('dasbor.kategori.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                            Kategori
                        </a>
                        <a href="{{ route('dasbor.sirkulasi.index') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('dasbor.sirkulasi.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                            Sirkulasi
                        </a>
                        <a href="{{ route('dasbor.laporan.index') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('dasbor.laporan.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                            Laporan
                        </a>
                    </nav>
                </div>
            </div>
            <div class="flex-shrink-0 w-14">
                <!-- Force sidebar to shrink to fit close icon -->
            </div>
        </div>

        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Navbar -->
            <header class="flex items-center justify-between h-16 bg-white border-b">
                 <div class="flex items-center px-4">
                    <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none focus:text-gray-700 md:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                 </div>

                 <div class="flex items-center pr-4">
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2 text-gray-700 focus:outline-none">
                            <span class="font-medium">{{ Auth::user()->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute right-0 w-48 mt-2 py-2 bg-white border rounded-md shadow-xl z-20">
                            @if (Route::has('profile.edit'))
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">Profil</a>
                            @endif

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">
                                    Log Out
                                </a>
                            </form>
                        </div>
                    </div>
                 </div>
            </header>

            <!-- Main content -->
            <main class="flex-1 p-4 sm:p-6 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
