<x-guest-layout>
    <div class="flex min-h-screen">
        
        <div class="hidden lg:flex w-1/2 bg-indigo-600 flex-col justify-center items-center text-white p-12 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full opacity-10">
                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#FFFFFF" d="M44.7,-76.4C58.9,-69.2,71.8,-59.1,81.6,-46.6C91.4,-34.1,98.2,-19.2,95.8,-5.2C93.5,8.8,82,21.9,71,33.4C60,44.9,49.5,54.9,37.6,63.1C25.7,71.3,12.4,77.7,-0.4,78.4C-13.2,79.1,-25.9,74.1,-37.4,66.6C-48.9,59.1,-59.2,49.1,-68.1,37.3C-77,25.5,-84.5,11.9,-83.4,-1.2C-82.3,-14.3,-72.6,-26.9,-62.4,-37.5C-52.2,-48.1,-41.5,-56.7,-30.2,-65.4C-18.9,-74.1,-7,-82.9,6.2,-93.6L19.4,-104.3" transform="translate(100 100)" />
                </svg>
            </div>

            <div class="relative z-10 text-center">
                <div class="mb-6 inline-flex h-20 w-20 items-center justify-center rounded-2xl bg-white text-indigo-600 shadow-lg">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h1 class="text-4xl font-bold mb-4">E-KLPCM RS</h1>
                <p class="text-indigo-100 text-lg max-w-md mx-auto leading-relaxed">
                    Sistem Analisis Kelengkapan Pengisian Catatan Medis Elektronik.
                </p>
            </div>
            
            <div class="absolute bottom-10 text-sm text-indigo-200">
                &copy; {{ date('Y') }} Unit Rekam Medis
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center bg-white p-8 sm:p-12 md:p-16">
            <div class="w-full max-w-md">
                
                <div class="lg:hidden text-center mb-8">
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-600 text-white mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">E-KLPCM</h2>
                </div>

                <div class="mb-10">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang Kembali</h2>
                    <p class="text-gray-500">Silakan masuk ke akun Anda untuk melanjutkan.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-6">
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                        <div class="relative">
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                                   class="w-full rounded-lg border border-gray-300 bg-white py-3 pl-10 pr-4 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 placeholder-gray-400"
                                   placeholder="Masukkan email Anda">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                   class="w-full rounded-lg border border-gray-300 bg-white py-3 pl-10 pr-4 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 placeholder-gray-400"
                                   placeholder="Masukkan password">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between mb-8">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                            <span class="ml-2 text-sm text-gray-600">Ingat Saya</span>
                        </label>
                        
                        @if (Route::has('password.request'))
                            <a class="text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:underline" href="{{ route('password.request') }}">
                                Lupa Password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="w-full flex justify-center items-center gap-2 rounded-lg bg-indigo-600 py-3.5 px-6 font-bold text-white shadow-lg hover:bg-indigo-700 hover:shadow-xl transition duration-200 transform hover:-translate-y-0.5">
                        Masuk ke Aplikasi
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>