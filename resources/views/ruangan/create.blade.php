<x-app-layout>
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-black">Tambah Ruangan Baru</h2>
        <a href="{{ route('ruangan.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600">&larr; Kembali</a>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-7 max-w-3xl">
        <form action="{{ route('ruangan.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Kode Ruangan <span class="text-red-500">*</span></label>
                    <input type="text" name="kode" placeholder="Contoh: MELATI / POLI-PD" required maxlength="10"
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition font-mono uppercase">
                    <p class="mt-1 text-xs text-gray-500">Maksimal 10 karakter. Harus unik.</p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Nama Ruangan / Unit <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" placeholder="Contoh: R. Inap Melati Kelas 1" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Jenis Pelayanan <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="tipe" required class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-3 pr-8 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition">
                            <option value="" disabled selected>-- Pilih Jenis --</option>
                            <option value="rawat_inap">Rawat Inap</option>
                            <option value="rawat_jalan">Rawat Jalan (Poliklinik)</option>
                            <option value="igd">IGD (Gawat Darurat)</option>
                            <option value="ok">Kamar Operasi</option>
                            <option value="penunjang">Penunjang (Lab/Rad)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('ruangan.index') }}" class="rounded-lg border border-gray-300 px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition">Batal</a>
                <button type="submit" class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-indigo-700 shadow-md transition">Simpan Data</button>
            </div>
        </form>
    </div>
</x-app-layout>