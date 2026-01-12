<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Analisis
            </h2>
            <nav>
                <ol class="flex items-center gap-2 text-sm">
                    <li><a class="text-gray-500 hover:text-blue-600" href="{{ route('dashboard') }}">Dashboard /</a></li>
                    <li class="font-bold text-blue-600">Detail</li>
                </ol>
            </nav>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            
            <div class="rounded-xl border border-stroke bg-white p-8 shadow-default mb-6">
                <div class="flex flex-col md:flex-row md:items-start md:justify-start gap-8 md:gap-16">
                    
                    <div class="min-w-fit">
                        <h3 class="text-2xl font-bold text-black mb-1">{{ $analisis->rekam_medis->pasien->nama }}</h3>
                        <div class="space-y-1 text-sm text-gray-600">
                            <p class="flex items-center gap-2">
                                <span class="w-20 text-gray-400 uppercase text-xs font-bold">No RM</span>
                                <span class="font-mono font-bold text-black text-base">{{ $analisis->rekam_medis->pasien->no_rm }}</span>
                            </p>
                            <p class="flex items-center gap-2">
                                <span class="w-20 text-gray-400 uppercase text-xs font-bold">DPJP</span>
                                <span class="font-medium">{{ $analisis->rekam_medis->dokter->nama }}</span>
                            </p>
                            <p class="flex items-center gap-2">
                                <span class="w-20 text-gray-400 uppercase text-xs font-bold">Ruangan</span>
                                <span>{{ $analisis->rekam_medis->ruangan->nama }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col items-start pt-1">
                        <span class="mb-2 text-xs font-bold uppercase text-gray-400">Status Analisis</span>
                        
                        @if($analisis->status == 'lengkap')
                            <span class="inline-flex items-center gap-2 rounded-full bg-green-100 py-1.5 px-4 text-sm font-bold text-green-700 border border-green-200 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                LENGKAP
                            </span>
                        @else
                            <div class="flex flex-col gap-2">
                                <span class="inline-flex w-fit items-center gap-2 rounded-full bg-red-100 py-1.5 px-4 text-sm font-bold text-red-700 border border-red-200 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    TIDAK LENGKAP
                                </span>
                                <p class="text-sm text-red-600 font-medium ml-1">
                                    <span class="text-gray-500 font-normal">Deadline:</span> 
                                    {{ \Carbon\Carbon::parse($analisis->deadline_revisi)->format('d/m/Y') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-stroke bg-white shadow-default overflow-hidden">
                <div class="border-b border-stroke py-5 px-7 bg-gray-50/50">
                    <h3 class="font-bold text-black text-lg">Daftar Ketidaklengkapan</h3>
                </div>
                
                <div class="p-0">
                    @if($analisis->detail_analisis->count() > 0)
                        <table class="w-full text-left">
                            <thead class="bg-white border-b border-gray-100">
                                <tr>
                                    <th class="py-4 px-7 font-bold text-xs uppercase text-gray-400 w-16">No</th>
                                    <th class="py-4 px-7 font-bold text-xs uppercase text-gray-400">Nama Formulir</th>
                                    <th class="py-4 px-7 font-bold text-xs uppercase text-gray-400">Kriteria Masalah</th>
                                    <th class="py-4 px-7 font-bold text-xs uppercase text-gray-400">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($analisis->detail_analisis as $index => $detail)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-4 px-7 text-gray-500">{{ $index + 1 }}</td>
                                    <td class="py-4 px-7">
                                        <span class="font-bold text-gray-800">{{ $detail->formulir->nama }}</span>
                                    </td>
                                    <td class="py-4 px-7">
                                        <span class="inline-block bg-orange-50 text-orange-700 px-2 py-1 rounded text-xs font-bold border border-orange-100">
                                            {{ $detail->kriteria->item }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-7 text-gray-600 italic">
                                        {{ $detail->catatan ?? '-' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="flex flex-col items-center justify-center py-12">
                            <div class="h-16 w-16 bg-green-100 rounded-full flex items-center justify-center mb-4 text-green-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900">Berkas Lengkap</h4>
                            <p class="text-gray-500">Tidak ada catatan ketidaklengkapan.</p>
                        </div>
                    @endif
                </div>
                
                <div class="border-t border-stroke p-7 bg-gray-50 flex justify-between items-center">
                    <div class="text-xs text-gray-500">
                        Petugas: <span class="font-bold text-gray-700">{{ Auth::user()->name }}</span> <br>
                        Tgl Input: {{ \Carbon\Carbon::parse($analisis->tgl_analisis)->format('d F Y') }}
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('dashboard') }}" class="rounded-lg border border-gray-300 bg-white py-2.5 px-6 font-medium text-gray-700 hover:bg-gray-50 hover:shadow-sm transition">
                            Kembali
                        </a>
                        <a href="{{ route('analisis.edit', $analisis->id) }}" class="rounded-lg bg-blue-600 py-2.5 px-6 font-medium text-white hover:bg-blue-700 shadow-md transition">
                            Edit / Revisi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>