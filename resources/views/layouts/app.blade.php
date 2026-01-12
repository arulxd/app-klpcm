<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'E-KLPCM') }} - Sistem Rekam Medis</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style> 
        body { font-family: 'Inter', sans-serif; } 
        [x-cloak] { display: none !important; }
        
        /* Style Tom Select */
        .ts-control { border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.875rem; box-shadow: none; }
        .ts-control.focus { border-color: #6366f1; box-shadow: 0 0 0 2px #e0e7ff; }
        .ts-dropdown { border-radius: 0.5rem; border: 1px solid #f3f4f6; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); z-index: 50; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100/50" 
      x-data="{ 
          sidebarOpen: window.innerWidth >= 1024,
          init() {
              if (localStorage.getItem('sidebarOpen') !== null) {
                  this.sidebarOpen = localStorage.getItem('sidebarOpen') === 'true';
              }
              window.addEventListener('resize', () => {
                  this.sidebarOpen = window.innerWidth >= 1024;
              });
          },
          toggleSidebar() {
              this.sidebarOpen = !this.sidebarOpen;
              localStorage.setItem('sidebarOpen', this.sidebarOpen);
          }
      }"
      x-init="init()">
    
    <div class="flex h-screen overflow-hidden">
        
        <aside :class="sidebarOpen ? 'translate-x-0 w-72' : '-translate-x-full lg:translate-x-0 lg:w-0'" 
               class="absolute left-0 top-0 z-50 flex h-screen flex-col overflow-y-hidden bg-white border-r border-gray-200 duration-300 ease-linear lg:static shadow-xl lg:shadow-none overflow-hidden transition-all">
            
            <div class="flex items-center justify-between gap-2 px-6 h-20 border-b border-gray-100 min-w-[18rem]">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="p-2 bg-blue-600 rounded-lg shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="text-xl font-bold text-gray-800 whitespace-nowrap">E-KLPCM</span>
                </a>
                <button @click.stop="toggleSidebar()" class="block lg:hidden text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear h-full min-w-[18rem]"
                 x-data="{ selected: '{{ (request()->is('analisis*')) ? 'KLPCM' : '' }}' }">
                
                <nav class="mt-5 px-4 lg:px-6 pb-6">
                    <ul class="mb-6 flex flex-col gap-1.5">
                        <li>
                            <a href="{{ route('dashboard') }}" class="group relative flex items-center gap-3 rounded-lg px-4 py-2.5 font-medium duration-300 ease-in-out {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/></svg>
                                Dashboard
                            </a>
                        </li>
                    </ul>

                    <div>
                        <h3 class="mb-4 ml-4 text-xs font-bold uppercase text-gray-400 whitespace-nowrap">ANALISIS BERKAS</h3>
                        <ul class="mb-6 flex flex-col gap-1.5">
                            <li>
                                <a class="group relative flex items-center justify-between gap-2.5 rounded-lg px-4 py-2.5 font-medium duration-300 ease-in-out hover:bg-gray-100 text-gray-700 cursor-pointer"
                                   @click.prevent="selected = (selected === 'KLPCM' ? '' : 'KLPCM')"
                                   :class="{ 'bg-gray-100 text-blue-600': selected === 'KLPCM' }">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                        KLPCM
                                    </div>
                                    <svg class="w-4 h-4 fill-current transition-transform duration-200" :class="{ 'rotate-180': selected === 'KLPCM' }" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </a>
                                <div class="overflow-hidden transition-all duration-300" x-show="selected === 'KLPCM'" x-cloak x-transition>
                                    <ul class="mt-2 mb-2 flex flex-col gap-1 pl-6 border-l-2 border-gray-100 ml-4">
                                        <li><a href="{{ route('analisis.create') }}" class="group flex items-center gap-2.5 rounded-md px-4 py-2 font-medium duration-300 ease-in-out {{ request()->routeIs('analisis.create') ? 'text-blue-600 bg-blue-50' : 'text-gray-500 hover:text-black' }}">Input Analisis Baru</a></li>
                                        <li><a href="{{ route('analisis.index') }}" class="group flex items-center gap-2.5 rounded-md px-4 py-2 font-medium duration-300 ease-in-out {{ request()->routeIs('analisis.index') ? 'text-blue-600 bg-blue-50' : 'text-gray-500 hover:text-black' }}">Data Analisis Terkini</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="mb-4 ml-4 text-xs font-bold uppercase text-gray-400 whitespace-nowrap mt-4">DATA MASTER</h3>
                        <ul class="mb-6 flex flex-col gap-1.5">
                            <li><a href="{{ route('rekam_medis.index') }}" class="group relative flex items-center gap-3 rounded-lg px-4 py-2.5 font-medium duration-300 {{ request()->routeIs('rekam_medis.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>Data Kunjungan</a></li>
                            <li><a href="{{ route('pasien.index') }}" class="group relative flex items-center gap-3 rounded-lg px-4 py-2.5 font-medium duration-300 {{ request()->routeIs('pasien.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>Data Pasien</a></li>
                            <li><a href="{{ route('dokter.index') }}" class="group relative flex items-center gap-3 rounded-lg px-4 py-2.5 font-medium duration-300 {{ request()->routeIs('dokter.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>Data Dokter</a></li>
                            <li><a href="{{ route('ruangan.index') }}" class="group relative flex items-center gap-3 rounded-lg px-4 py-2.5 font-medium duration-300 {{ request()->routeIs('ruangan.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>Data Ruangan</a></li>
                            <li>
                                <a href="{{ route('formulir.index') }}" class="group relative flex items-center gap-3 rounded-lg px-4 py-2.5 font-medium duration-300 {{ request()->routeIs('formulir.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Data Formulir
                                </a>
                            </li>
                            @if(Auth::user()->role === 'admin')
                            <li><a href="{{ route('users.index') }}" class="group relative flex items-center gap-3 rounded-lg px-4 py-2.5 font-medium duration-300 {{ request()->routeIs('users.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>Data User</a></li>
                            @endif
                        </ul>
                    </div>

                    <div>
                        <h3 class="mb-4 ml-4 text-xs font-bold uppercase text-gray-400 whitespace-nowrap mt-4">OUTPUT</h3>
                        <ul class="mb-6 flex flex-col gap-1.5">
                            <li><a href="{{ route('laporan.index') }}" class="group relative flex items-center gap-3 rounded-lg px-4 py-2.5 font-medium duration-300 {{ request()->routeIs('laporan.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>Laporan</a></li>
                        </ul>
                    </div>
                </nav>
            </div>
        </aside>
        
        <div class="relative flex flex-1 flex-col h-screen overflow-y-auto overflow-x-hidden">
            
            <header class="sticky top-0 z-40 flex w-full bg-white border-b border-gray-200 shrink-0">
                <div class="flex flex-grow items-center justify-between px-4 py-4 md:px-6 2xl:px-11 h-20">
                    <div class="flex items-center gap-2 sm:gap-4">
                        <button @click.stop="toggleSidebar()" class="block p-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 focus:outline-none">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                    </div>
                    <div class="hidden sm:block w-96">
                        <form action="{{ route('analisis.index') }}" method="GET">
                            <div class="relative">
                                <button type="submit" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></button>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Pasien / No RM (Global)..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 text-gray-600 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                            </div>
                        </form>
                    </div>
                    <div class="flex items-center gap-3 2xsm:gap-7">
                        <div class="relative" x-data="{ dropdownOpen: false }">
                            <a @click.prevent="dropdownOpen = !dropdownOpen" class="flex items-center gap-4 cursor-pointer select-none" href="#">
                                <span class="hidden text-right lg:block"><span class="block text-sm font-bold text-black">{{ Auth::user()->name }}</span><span class="block text-xs font-medium text-gray-500">Administrator</span></span>
                                <img class="h-11 w-11 rounded-full object-cover bg-gray-200" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=EBF4FF&color=3B82F6&bold=true" alt="User">
                                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="dropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </a>
                            <div x-show="dropdownOpen" @click.outside="dropdownOpen = false" class="absolute right-0 mt-4 flex w-48 flex-col rounded-xl border border-gray-200 bg-white shadow-lg p-2" style="display: none;">
                                <form method="POST" action="{{ route('logout') }}">@csrf<button class="flex w-full items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>Log Out</button></form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-grow p-4 md:p-6 2xl:p-10">
                {{ $slot }}
            </main>

            <footer class="mt-auto border-t border-gray-200 bg-white py-6 px-4 md:px-6 2xl:px-11">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-500">
                    <p>
                        &copy; {{ date('Y') }} <span class="font-bold text-blue-600">E-KLPCM</span>. Sistem Analisis Rekam Medis.
                    </p>
                    <div class="flex items-center gap-2">
                        <span>Ver 1.0.0</span>
                        <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                        <span>Dikembangkan oleh <span class="font-medium text-gray-600">Unit Rekam Medis</span></span>
                    </div>
                </div>
            </footer>

        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })

                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                })
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                })
            @endif
        });
    </script>
</body>
</html>