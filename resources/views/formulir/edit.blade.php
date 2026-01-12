<x-app-layout>
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-black">Edit Formulir</h2>
        <a href="{{ route('formulir.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">&larr; Kembali</a>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-8 max-w-2xl">
        <form action="{{ route('formulir.update', $formulir->id) }}" method="POST">
            @csrf @method('PUT')
            
            <div class="mb-6">
                <label class="mb-2 block text-sm font-bold text-gray-800">Nama Formulir <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ $formulir->nama }}" required autofocus
                       class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm font-medium text-gray-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition">
            </div>

            <div class="mb-6">
                <label class="mb-2 block text-sm font-bold text-gray-800">Kategori Formulir <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select name="kategori" required class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-3 pr-8 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition">
                        <option value="manual" {{ $formulir->kategori == 'manual' ? 'selected' : '' }}>Manual (Kertas)</option>
                        <option value="elektronik" {{ $formulir->kategori == 'elektronik' ? 'selected' : '' }}>Elektronik (E-RME)</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 border-t border-gray-100 pt-6">
                <a href="{{ route('formulir.index') }}" class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition shadow-sm">Batal</a>
                <button type="submit" class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-indigo-700 transition shadow-md hover:shadow-lg">Update Perubahan</button>
            </div>
        </form>
    </div>
</x-app-layout>