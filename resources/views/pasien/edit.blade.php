<x-app-layout>
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-black">Edit Data Pasien</h2>
        <a href="{{ route('pasien.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600">&larr; Kembali</a>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-7">
        <form action="{{ route('pasien.update', $pasien->id) }}" method="POST">
            @csrf @method('PUT')
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Nomor Rekam Medis</label>
                    <input type="text" name="no_rm" value="{{ $pasien->no_rm }}" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition font-mono">
                </div>

               <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">NIK (KTP)</label>
                    <input type="text" 
                        name="nik" 
                        value="{{ old('nik', $pasien->nik) }}" 
                        placeholder="16 Digit Angka"
                        maxlength="16"
                        inputmode="numeric"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16)"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition font-mono">
                    
                    @error('nik')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-bold text-gray-700">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ $pasien->nama }}" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" value="{{ $pasien->tgl_lahir }}" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition text-gray-600">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">Jenis Kelamin</label>
                    <div class="relative">
                        <select name="jenis_kelamin" required class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-3 pr-8 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition">
                            <option value="L" {{ $pasien->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ $pasien->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('pasien.index') }}" class="rounded-lg border border-gray-300 px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition">Batal</a>
                <button type="submit" class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-indigo-700 shadow-md transition">Update Perubahan</button>
            </div>
        </form>
    </div>
</x-app-layout>