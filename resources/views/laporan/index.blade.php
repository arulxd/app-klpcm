<x-app-layout>
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-black">Pusat Laporan & Statistik</h2>
        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600">&larr; Kembali</a>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-8 max-w-3xl mx-auto">
        
        <div class="text-center mb-8">
            <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-indigo-50 mb-4">
                <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900">Generator Laporan KLPCM</h3>
            <p class="text-sm text-gray-500 mt-1">Pilih jenis laporan dan parameter yang dibutuhkan.</p>
        </div>

        <form action="{{ route('laporan.cetak') }}" method="POST" target="_blank" 
              x-data="{ tipe: 'umum' }"> @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Dari Tanggal</label>
                    <input type="date" name="tgl_awal" required value="{{ date('Y-m-01') }}"
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition text-gray-600">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Sampai Tanggal</label>
                    <input type="date" name="tgl_akhir" required value="{{ date('Y-m-d') }}"
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition text-gray-600">
                </div>
            </div>

            <hr class="border-gray-100 my-6">

            <div class="mb-6">
                <label class="mb-3 block text-sm font-bold text-gray-700">Jenis Laporan</label>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <label class="cursor-pointer">
                        <input type="radio" name="jenis_laporan" value="umum" x-model="tipe" class="peer sr-only">
                        <div class="rounded-lg border border-gray-200 p-4 text-center hover:bg-gray-50 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 transition">
                            <span class="font-bold text-sm">Rekap Umum</span>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="jenis_laporan" value="ruangan" x-model="tipe" class="peer sr-only">
                        <div class="rounded-lg border border-gray-200 p-4 text-center hover:bg-gray-50 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 transition">
                            <span class="font-bold text-sm">Per Ruangan</span>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="jenis_laporan" value="dokter" x-model="tipe" class="peer sr-only">
                        <div class="rounded-lg border border-gray-200 p-4 text-center hover:bg-gray-50 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 transition">
                            <span class="font-bold text-sm">Per Dokter</span>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="jenis_laporan" value="formulir" x-model="tipe" class="peer sr-only">
                        <div class="rounded-lg border border-gray-200 p-4 text-center hover:bg-gray-50 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 transition">
                            <span class="font-bold text-sm">Per Formulir</span>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="jenis_laporan" value="rekap_ruangan" x-model="tipe" class="peer sr-only">
                        <div class="rounded-lg border border-gray-200 p-4 text-center hover:bg-gray-50 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 transition h-full flex items-center justify-center">
                            <span class="font-bold text-sm">Rekap Jumlah<br>Per Ruangan</span>
                        </div>
                    </label>
                </div>
            </div>

           <div x-show="tipe == 'ruangan' || tipe == 'rekap_ruangan'" class="mb-8 p-5 bg-indigo-50 rounded-xl border border-indigo-100" style="display: none;">
                <label class="mb-2 block text-sm font-bold text-indigo-900">Filter Ruangan</label>
                <p class="text-xs text-indigo-600 mb-2">Pilih satu ruangan spesifik atau semua ruangan.</p>
                
                <select name="ruangan_id" class="w-full rounded-lg border-indigo-300 px-4 py-3 text-sm focus:ring-indigo-500">
                    <option value="all" selected>-- SEMUA RUANGAN --</option>
                    
                    @foreach($ruangan as $r)
                        <option value="{{ $r->id }}">{{ $r->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div x-show="tipe == 'dokter'" class="mb-8 p-5 bg-indigo-50 rounded-xl border border-indigo-100" style="display: none;">
                <label class="mb-2 block text-sm font-bold text-indigo-900">Pilih Dokter DPJP</label>
                <select name="dokter_id" class="w-full rounded-lg border-indigo-300 px-4 py-3 text-sm focus:ring-indigo-500">
                    <option value="" disabled selected>-- Pilih Dokter --</option>
                    @foreach($dokter as $d)
                        <option value="{{ $d->id }}">{{ $d->nama }} ({{ $d->spesialis }})</option>
                    @endforeach
                </select>
            </div>

            <div x-show="tipe == 'formulir'" class="mb-8 p-5 bg-indigo-50 rounded-xl border border-indigo-100" style="display: none;">
                <label class="mb-2 block text-sm font-bold text-indigo-900">Pilih Formulir Spesifik</label>
                <p class="text-xs text-indigo-600 mb-2">Laporan ini menampilkan daftar ketidaklengkapan berdasarkan formulir.</p>
                
                <select name="formulir_id" class="w-full rounded-lg border-indigo-300 px-4 py-3 text-sm focus:ring-indigo-500">
                    <option value="all" selected>-- TAMPILKAN SEMUA (GLOBAL) --</option>
                    
                    @foreach($formulir as $f)
                        <option value="{{ $f->id }}">{{ $f->nama }}</option>
                    @endforeach
                </select>
            </div>

            

            <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-xl bg-indigo-600 py-3.5 px-6 font-bold text-white hover:bg-indigo-700 shadow-md hover:shadow-lg transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Download PDF
            </button>
        </form>
    </div>
</x-app-layout>