<x-app-layout>
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-black text-2xl">Master Data Formulir</h2>
    </div>

    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        
        <form action="{{ route('formulir.index') }}" method="GET" class="flex items-center gap-2">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif

            <label class="text-sm font-medium text-gray-600">Tampilkan</label>
            <select name="per_page" onchange="this.form.submit()" 
                    class="rounded-lg border border-gray-300 bg-white py-1.5 pl-3 pr-8 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 cursor-pointer outline-none shadow-sm transition">
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
            </select>
            <span class="text-sm font-medium text-gray-600">Data</span>
        </form>

        <div class="flex gap-3 w-full sm:w-auto">
            <form action="{{ route('formulir.index') }}" method="GET" class="relative w-full sm:w-64">
                @if(request('per_page'))
                    <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                @endif
                
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Formulir..." 
                       class="w-full rounded-xl border border-gray-200 bg-white py-2 pl-10 pr-4 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition shadow-sm">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
            </form>
            
            <a href="{{ route('formulir.create') }}" class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-bold text-white hover:bg-indigo-700 transition shadow-md">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-green-800 shadow-sm flex items-center gap-2">
            <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-red-800 shadow-sm flex items-center gap-2">
            <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase text-gray-500 font-bold">
                    <th class="px-6 py-4 text-center w-20">No</th>
                    <th class="px-6 py-4">Nama Formulir</th>
                    <th class="px-6 py-4 text-center">Kategori</th>
                    <th class="px-6 py-4 text-center w-40">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($formulir as $index => $item)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 text-center text-gray-500">
                        {{ $formulir->firstItem() + $index }}
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-900">
                        {{ $item->nama }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($item->kategori == 'manual')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                MANUAL
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                ELEKTRONIK
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('formulir.edit', $item->id) }}" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Edit">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            
                            <form action="{{ route('formulir.destroy', $item->id) }}" method="POST" class="delete-form inline-block">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500 italic">Belum ada data formulir.</td></tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="text-sm text-gray-500">
                Menampilkan <b>{{ $formulir->firstItem() ?? 0 }}</b> s/d <b>{{ $formulir->lastItem() ?? 0 }}</b> dari total <b>{{ $formulir->total() }}</b> data
            </div>
            
            <div>
                {{ $formulir->links() }} 
            </div>
        </div>
    </div>
</x-app-layout>