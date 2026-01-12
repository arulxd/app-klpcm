<x-app-layout>
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-black">Edit Data Kunjungan</h2>
        <a href="{{ route('rekam_medis.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600">&larr; Kembali</a>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-7">
        <form action="{{ route('rekam_medis.update', $rm->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-bold text-gray-700">Pilih Pasien <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="pasien_id" class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-3 pr-8 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition" required>
                            <option value="" disabled>-- Cari Nama Pasien / No RM --</option>
                            @foreach($pasien as $p)
                                <option value="{{ $p->id }}" {{ $rm->pasien_id == $p->id ? 'selected' : '' }}>
                                    {{ $p->no_rm }} - {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">No Registrasi / SEP <span class="text-red-500">*</span></label>
                    <input type="text" name="no_registrasi" value="{{ $rm->no_registrasi }}" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition font-mono uppercase">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Cara Masuk <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="cara_masuk" class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-3 pr-8 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition" required>
                            <option value="igd" {{ $rm->cara_masuk == 'igd' ? 'selected' : '' }}>IGD (Instalasi Gawat Darurat)</option>
                            <option value="poli" {{ $rm->cara_masuk == 'poli' ? 'selected' : '' }}>POLI (Rawat Jalan)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Dokter DPJP <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="dokter_id" class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-3 pr-8 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition" required>
                            <option value="" disabled>-- Pilih Dokter --</option>
                            @foreach($dokter as $d)
                                <option value="{{ $d->id }}" {{ $rm->dokter_id == $d->id ? 'selected' : '' }}>
                                    {{ $d->nama }} ({{ $d->spesialis }})
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Ruangan Perawatan <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="ruangan_id" class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-3 pr-8 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition" required>
                            <option value="" disabled>-- Pilih Ruangan --</option>
                            @foreach($ruangan as $r)
                                <option value="{{ $r->id }}" {{ $rm->ruangan_id == $r->id ? 'selected' : '' }}>
                                    {{ $r->nama }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Tanggal Masuk <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="tgl_masuk" 
                           value="{{ \Carbon\Carbon::parse($rm->tgl_masuk)->format('Y-m-d\TH:i') }}"
                           required
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition text-gray-600">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Tanggal Pulang <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="tgl_pulang" 
                           value="{{ \Carbon\Carbon::parse($rm->tgl_pulang)->format('Y-m-d\TH:i') }}"
                           required
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition text-gray-600">
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-bold text-gray-700">Jenis Pembayaran <span class="text-red-500">*</span></label>
                    <div class="flex gap-6 mt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="jenis_bayar" value="bpjs" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 border-gray-300" {{ $rm->jenis_bayar == 'bpjs' ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-gray-700">BPJS Kesehatan</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="jenis_bayar" value="umum" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 border-gray-300" {{ $rm->jenis_bayar == 'umum' ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-gray-700">Umum / Pribadi</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="jenis_bayar" value="asuransi" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 border-gray-300" {{ $rm->jenis_bayar == 'asuransi' ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-gray-700">Asuransi Lain</span>
                        </label>
                    </div>
                </div>

            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('rekam_medis.index') }}" class="rounded-lg border border-gray-300 px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition">Batal</a>
                <button type="submit" class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-indigo-700 shadow-md transition">Update Perubahan</button>
            </div>
        </form>
    </div>
</x-app-layout>