<x-app-layout>
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-black text-2xl">Data Kunjungan Pasien</h2>
    </div>

    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form action="{{ route('rekam_medis.index') }}" method="GET" class="flex items-center gap-2">
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
            <form action="{{ route('rekam_medis.index') }}" method="GET" class="relative w-full sm:w-64">
                @if(request('per_page'))
                    <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Pasien / No RM..." 
                       class="w-full rounded-xl border border-gray-200 bg-white py-2 pl-10 pr-4 text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition shadow-sm">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
            </form>
            <a href="{{ route('rekam_medis.create') }}" class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-bold text-white hover:bg-indigo-700 transition shadow-md">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Registrasi
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

    <div class="w-full rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase text-gray-500 font-bold">
                        <th class="px-6 py-4 text-center w-10">No</th>
                        <th class="px-6 py-4">No. RM</th>
                        <th class="px-6 py-4">Nama Pasien</th>
                        <th class="px-6 py-4">Tgl Masuk</th>
                        <th class="px-6 py-4">Tgl Pulang</th>
                        <th class="px-6 py-4">Dokter DPJP</th>
                        <th class="px-6 py-4">Ruangan</th>
                        <th class="px-6 py-4 text-center">Status Analisis</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($rekam_medis as $index => $item)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 text-center text-gray-500">
                            {{ $rekam_medis->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 font-mono text-indigo-600 font-bold">
                            {{ $item->pasien->no_rm ?? '-' }}
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-900">
                            {{ $item->pasien->nama ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ \Carbon\Carbon::parse($item->tgl_masuk)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ \Carbon\Carbon::parse($item->tgl_pulang)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $item->dokter->nama ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $item->ruangan->nama ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusAnalisis = 'Belum';
                                $statusClass = 'bg-gray-100 text-gray-600 border-gray-200';
                                if($item->analisis()->exists()) {
                                    if($item->analisis->status == 'lengkap') {
                                        $statusAnalisis = 'Lengkap';
                                        $statusClass = 'bg-green-100 text-green-800 border-green-200';
                                    } else {
                                        $statusAnalisis = 'Revisi';
                                        $statusClass = 'bg-red-100 text-red-800 border-red-200';
                                    }
                                }
                            @endphp
                            
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $statusClass }}">
                                {{ strtoupper($statusAnalisis) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button type="button" 
                                    onclick="openModal({{ json_encode([
                                        'no_rm' => $item->pasien->no_rm ?? '-',
                                        'nama_pasien' => $item->pasien->nama ?? '-',
                                        'tgl_lahir' => \Carbon\Carbon::parse($item->pasien->tgl_lahir)->translatedFormat('d F Y'),
                                        'no_registrasi' => $item->no_registrasi ?? '-', 
                                        'cara_masuk' => $item->cara_masuk ?? '-',
                                        'dokter' => $item->dokter->nama ?? '-',
                                        'ruangan' => $item->ruangan->nama ?? '-',
                                        'tgl_masuk' => \Carbon\Carbon::parse($item->tgl_masuk)->translatedFormat('d F Y'),
                                        'tgl_pulang' => \Carbon\Carbon::parse($item->tgl_pulang)->translatedFormat('d F Y'),
                                        'status' => $statusAnalisis,
                                        'status_class' => $statusClass
                                    ]) }})"
                                    class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Lihat Detail">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>

                                <a href="{{ route('rekam_medis.edit', $item->id) }}" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Edit">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                
                                <form action="{{ route('rekam_medis.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus data kunjungan ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="px-6 py-8 text-center text-gray-500 italic">Belum ada data kunjungan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="text-sm text-gray-500">
                Menampilkan <b>{{ $rekam_medis->firstItem() ?? 0 }}</b> s/d <b>{{ $rekam_medis->lastItem() ?? 0 }}</b> dari total <b>{{ $rekam_medis->total() }}</b> data
            </div>
            <div>{{ $rekam_medis->links() }}</div>
        </div>
    </div>

    <div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl w-full">
                
                <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-b border-gray-100">
                    <h3 class="text-xl leading-6 font-bold text-gray-900" id="modal-title">Detail Lengkap Kunjungan</h3>
                    <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="bg-white px-6 pt-5 pb-6">
                    <div class="space-y-6">
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 bg-blue-50 p-4 rounded-xl border border-blue-100">
                            <div class="flex-shrink-0 h-16 w-16 rounded-full bg-white border border-blue-200 flex items-center justify-center text-blue-600 font-bold text-2xl shadow-sm">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div class="text-center sm:text-left flex-grow">
                                <h4 class="text-2xl font-bold text-gray-900" id="modal-nama">-</h4>
                                <div class="flex flex-col sm:flex-row gap-3 mt-1 justify-center sm:justify-start">
                                    <p class="text-sm text-gray-600 bg-white px-2 py-1 rounded border border-gray-200">No. RM: <span id="modal-rm" class="font-mono font-bold text-black">-</span></p>
                                    <p class="text-sm text-gray-600 bg-white px-2 py-1 rounded border border-gray-200">No. Reg: <span id="modal-no-reg" class="font-mono font-bold text-black">-</span></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span id="modal-status" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold border">
                                    -
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                            
                            <div class="space-y-4">
                                <h5 class="font-bold text-gray-900 border-b pb-2">Informasi Pasien</h5>
                                <div>
                                    <span class="block text-gray-400 text-xs uppercase font-bold mb-1">Tanggal Lahir</span>
                                    <span class="block text-gray-800 font-medium text-base" id="modal-tgl-lahir">-</span>
                                </div>
                                <div>
                                    <span class="block text-gray-400 text-xs uppercase font-bold mb-1">Cara Masuk</span>
                                    <span class="block text-gray-800 font-medium text-base" id="modal-cara-masuk">-</span>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h5 class="font-bold text-gray-900 border-b pb-2">Informasi Rawat</h5>
                                <div>
                                    <span class="block text-gray-400 text-xs uppercase font-bold mb-1">Dokter DPJP</span>
                                    <span class="block text-gray-800 font-medium text-base" id="modal-dokter">-</span>
                                </div>
                                <div>
                                    <span class="block text-gray-400 text-xs uppercase font-bold mb-1">Ruangan Perawatan</span>
                                    <span class="block text-gray-800 font-medium text-base" id="modal-ruangan">-</span>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h5 class="font-bold text-gray-900 border-b pb-2">Durasi Perawatan</h5>
                                <div>
                                    <span class="block text-gray-400 text-xs uppercase font-bold mb-1">Tanggal Masuk</span>
                                    <span class="block text-gray-800 font-medium text-base" id="modal-tgl-masuk">-</span>
                                </div>
                                <div>
                                    <span class="block text-gray-400 text-xs uppercase font-bold mb-1">Tanggal Pulang</span>
                                    <span class="block text-gray-800 font-medium text-base" id="modal-tgl-pulang">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse">
                    <button type="button" onclick="closeModal()" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-6 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Tutup Detail
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(data) {
            document.getElementById('modal-nama').innerText = data.nama_pasien;
            document.getElementById('modal-rm').innerText = data.no_rm;
            
            // Data Baru Ditampilkan
            document.getElementById('modal-no-reg').innerText = data.no_registrasi;
            document.getElementById('modal-cara-masuk').innerText = data.cara_masuk;

            document.getElementById('modal-tgl-lahir').innerText = data.tgl_lahir;
            document.getElementById('modal-dokter').innerText = data.dokter;
            document.getElementById('modal-ruangan').innerText = data.ruangan;
            document.getElementById('modal-tgl-masuk').innerText = data.tgl_masuk;
            document.getElementById('modal-tgl-pulang').innerText = data.tgl_pulang;
            
            const badge = document.getElementById('modal-status');
            badge.innerText = data.status.toUpperCase();
            badge.className = "inline-flex items-center px-3 py-1 rounded-full text-sm font-bold border " + data.status_class;
            
            document.getElementById('detailModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }
        
        document.addEventListener('keydown', function(event) { 
            if (event.key === "Escape") closeModal(); 
        });
    </script>
</x-app-layout>