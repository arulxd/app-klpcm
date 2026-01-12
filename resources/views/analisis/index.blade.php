<x-app-layout>
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-black text-2xl">Data Analisis Lengkap</h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li><a class="font-medium text-gray-500 hover:text-blue-600" href="{{ route('dashboard') }}">Dashboard /</a></li>
                <li class="font-medium text-blue-600">Data Analisis</li>
            </ol>
        </nav>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white px-6 pt-6 pb-6 shadow-sm">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            
            <form action="{{ route('analisis.index') }}" method="GET" class="w-full md:w-96 relative">
                <button type="submit" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Pasien / No RM..." 
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 pl-12 pr-4 text-gray-600 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
            </form>

            <a href="{{ route('analisis.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 py-2.5 px-6 text-center font-medium text-white hover:bg-blue-700 transition shadow-sm hover:shadow-md w-full md:w-auto">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Input Baru
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50/50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                        <th class="px-4 py-4 rounded-l-xl">Pasien</th>
                        <th class="px-4 py-4 text-center">DPJP</th>
                        <th class="px-4 py-4 text-center">Ruangan</th>
                        <th class="px-4 py-4 text-center">Pengembalian</th>
                        <th class="px-4 py-4 text-center">Status</th>
                        <th class="px-4 py-4 text-center rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($data_rm as $item)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold border border-blue-100">
                                    {{ substr($item->pasien->nama, 0, 1) }}
                                </div>
                                <div>
                                    <h5 class="font-bold text-gray-800">{{ $item->pasien->nama }}</h5>
                                    <p class="text-xs text-gray-500 font-mono">{{ $item->pasien->no_rm }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <p class="text-sm text-gray-800 font-medium">{{ $item->dokter->nama }}</p>
                            <p class="text-xs text-gray-500">{{ $item->dokter->spesialis }}</p>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <p class="text-sm text-gray-700">{{ $item->ruangan->nama }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-600">{{ strtoupper($item->cara_masuk) }}</span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            @php $statusRM = $item->status_pengembalian; @endphp
                            <div class="flex flex-col items-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold border uppercase {{ $statusRM['color'] }}">
                                    {{ $statusRM['label'] }}
                                </span>
                                <span class="text-xs text-gray-500 mt-1">{{ $statusRM['selisih'] }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($item->analisis)
                                @if($item->analisis->status == 'lengkap')
                                    <span class="inline-flex rounded-full bg-green-50 border border-green-200 py-1 px-3 text-xs font-bold text-green-600">LENGKAP</span>
                                @else
                                    <span class="inline-flex rounded-full bg-red-50 border border-red-200 py-1 px-3 text-xs font-bold text-red-600">REVISI</span>
                                @endif
                            @else
                                <span class="text-gray-400 italic text-xs">Pending</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($item->analisis)
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('analisis.show', $item->analisis->id) }}" class="p-2 bg-white border rounded-lg text-gray-600 hover:text-blue-600 hover:border-blue-300 transition" title="Detail"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg></a>
                                <a href="{{ route('analisis.edit', $item->analisis->id) }}" class="p-2 bg-white border rounded-lg text-gray-600 hover:text-green-600 hover:border-green-300 transition" title="Edit"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></a>
                                <form action="{{ route('analisis.destroy', $item->analisis->id) }}" method="POST" onsubmit="return confirm('Hapus permanen?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 bg-white border rounded-lg text-gray-600 hover:text-red-600 hover:border-red-300 transition" title="Hapus"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                </form>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="p-5 text-center text-gray-500">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $data_rm->links() }}
        </div>
    </div>
</x-app-layout>