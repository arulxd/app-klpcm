<x-app-layout>
   <style>
    /* 1. MENGUBAH BACKGROUND JADI PUTIH BERSIH */
    .ts-control, 
    .ts-wrapper.single.input-active .ts-control,
    .ts-wrapper.single .ts-control {
        background-color: #ffffff !important; /* Wajib Putih */
        background-image: none !important;    /* Hapus gradasi bawaan jika ada */
        border: 1px solid #d1d5db;            /* Border abu-abu halus */
        border-radius: 0.5rem;                /* Rounded LG */
        padding: 0.75rem 1rem;                /* Spacing lega */
        font-size: 0.875rem;                  /* Text SM */
        box-shadow: none !important;          /* Hapus bayangan dalam */
    }

    /* 2. EFEK FOKUS (RING UNGU) */
    .ts-control.focus {
        border-color: #6366f1 !important;     /* Indigo-500 */
        box-shadow: 0 0 0 2px #e0e7ff !important; /* Ring Indigo-100 */
    }

    /* 3. DROPDOWN LIST */
    .ts-dropdown {
        background-color: #ffffff !important;
        border-radius: 0.5rem;
        border: 1px solid #f3f4f6;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        z-index: 50;
        margin-top: 4px;
    }

    /* 4. ITEM YANG DIPILIH (ACTIVE HOVER) */
    .ts-dropdown .active {
        background-color: #e0e7ff !important; /* Indigo-50 */
        color: #4338ca !important;            /* Indigo-700 */
    }
    
    /* 5. TEXT INPUT DI DALAMNYA */
    .ts-control > input {
        color: #111827 !important; /* Text Gray-900 */
    }
    
    /* 6. Placeholder */
    .ts-wrapper.single .ts-control .item {
        color: #111827; /* Warna teks item yang sudah dipilih */
    }
</style>

    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-black">Registrasi Kunjungan Baru</h2>
        <a href="{{ route('rekam_medis.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600">&larr; Kembali</a>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-7">
        <form action="{{ route('rekam_medis.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-bold text-gray-700">Pilih Pasien <span class="text-red-500">*</span></label>
                    
                    <select id="select-pasien" name="pasien_id" placeholder="Ketik Nama atau No RM..." autocomplete="off" required>
                        <option value="">Ketik Nama atau No RM...</option>
                        @foreach($pasien as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->no_rm }} - {{ $p->nama }} (NIK: {{ $p->nik ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Ketik minimal 1 huruf untuk mencari.</p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">No Registrasi / SEP <span class="text-red-500">*</span></label>
                    <input type="text" name="no_registrasi" placeholder="Contoh: REG-2025-001" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition font-mono uppercase">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Cara Masuk <span class="text-red-500">*</span></label>
                    <select name="cara_masuk" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition bg-white" required>
                        <option value="igd">IGD (Instalasi Gawat Darurat)</option>
                        <option value="poli">POLI (Rawat Jalan)</option>
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Dokter DPJP <span class="text-red-500">*</span></label>
                    <select name="dokter_id" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition bg-white" required>
                        <option value="" disabled selected>-- Pilih Dokter --</option>
                        @foreach($dokter as $d)
                            <option value="{{ $d->id }}">{{ $d->nama }} ({{ $d->spesialis }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Ruangan Perawatan <span class="text-red-500">*</span></label>
                    <select name="ruangan_id" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition bg-white" required>
                        <option value="" disabled selected>-- Pilih Ruangan --</option>
                        @foreach($ruangan as $r)
                            <option value="{{ $r->id }}">{{ $r->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Tanggal Masuk <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="tgl_masuk" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition text-gray-600">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Tanggal Pulang <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="tgl_pulang" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition text-gray-600">
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-bold text-gray-700">Jenis Pembayaran <span class="text-red-500">*</span></label>
                    <div class="flex gap-6 mt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="jenis_bayar" value="bpjs" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 border-gray-300" checked>
                            <span class="text-sm font-medium text-gray-700">BPJS Kesehatan</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="jenis_bayar" value="umum" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                            <span class="text-sm font-medium text-gray-700">Umum / Pribadi</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="jenis_bayar" value="asuransi" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                            <span class="text-sm font-medium text-gray-700">Asuransi Lain</span>
                        </label>
                    </div>
                </div>

            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('rekam_medis.index') }}" class="rounded-lg border border-gray-300 px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition">Batal</a>
                <button type="submit" class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-indigo-700 shadow-md transition">Simpan Registrasi</button>
            </div>
        </form>
    </div>

   <script>
        document.addEventListener('DOMContentLoaded', function() {
            var selectElement = document.getElementById('select-pasien');
            
            if(selectElement) {
                var tom = new TomSelect("#select-pasien",{
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    },
                    placeholder: 'Ketik Nama atau No RM...',
                });

                // LOGIKA BARU: AUTO SELECT PASIEN BARU
                // Ambil ID dari Controller (PHP)
                var newPatientId = "{{ $selectedPatientId ?? '' }}";

                // Jika ada ID, suruh Tom Select memilihnya
                if(newPatientId) {
                    tom.setValue(newPatientId);
                }
            }
        });
    </script>
</x-app-layout>