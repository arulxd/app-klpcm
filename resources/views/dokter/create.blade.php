<x-app-layout>
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-black">Tambah Dokter Baru</h2>
        <a href="{{ route('dokter.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600">&larr; Kembali</a>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-7">
        <form action="{{ route('dokter.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Nama Lengkap & Gelar</label>
                    <input type="text" name="nama" placeholder="Contoh: dr. Budi Santoso, Sp.PD" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">NIK / ID Pegawai</label>
                    <input type="text" name="nik" placeholder="Contoh: D-001" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Spesialisasi</label>
                    <input type="text" name="spesialis" placeholder="Contoh: Penyakit Dalam" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition">
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('dokter.index') }}" class="rounded-lg border border-gray-300 px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition">Batal</a>
                <button type="submit" class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-indigo-700 shadow-md transition">Simpan Data</button>
            </div>
        </form>
    </div>
</x-app-layout>