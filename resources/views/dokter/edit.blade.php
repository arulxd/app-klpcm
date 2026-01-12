<x-app-layout>
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-black">Edit Data Dokter</h2>
        <a href="{{ route('dokter.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600">&larr; Kembali</a>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-7">
        <form action="{{ route('dokter.update', $dokter->id) }}" method="POST">
            @csrf @method('PUT')
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Nama Lengkap & Gelar</label>
                    <input type="text" name="nama" value="{{ $dokter->nama }}" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">NIK / ID Pegawai</label>
                    <input type="text" name="nik" value="{{ $dokter->nik }}" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Spesialisasi</label>
                    <input type="text" name="spesialis" value="{{ $dokter->spesialis }}" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Status Dokter</label>
                    <select name="is_active" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition">
                        <option value="1" {{ $dokter->is_active ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !$dokter->is_active ? 'selected' : '' }}>Non-Aktif (Cuti/Keluar)</option>
                    </select>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('dokter.index') }}" class="rounded-lg border border-gray-300 px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition">Batal</a>
                <button type="submit" class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-indigo-700 shadow-md transition">Update Perubahan</button>
            </div>
        </form>
    </div>
</x-app-layout>