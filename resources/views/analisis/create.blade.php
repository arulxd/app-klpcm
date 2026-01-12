@php
    // Data Pasien
    $rm_options = $list_rm->map(function($rm) {
        return [
            'value' => $rm->id,
            'label' => $rm->pasien->no_rm . ' - ' . $rm->pasien->nama . ' (Pulang: ' . \Carbon\Carbon::parse($rm->tgl_pulang)->format('d/m/Y') . ')',
        ];
    })->values();

    // Data Form
    $js_form_list = $list_form->map(function($f) {
        return ['value' => $f->id, 'label' => $f->nama];
    });
    
    // Data Kriteria
    $js_kriteria_list = collect();
    foreach($list_kriteria as $kategori => $items) {
        foreach($items as $item) {
            $js_kriteria_list->push([
                'value' => $item->id, 
                'label' => strtoupper($kategori) . ' - ' . $item->item, 
            ]);
        }
    }
@endphp

<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.default.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    
    <style>
        .ts-control {
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            padding: 10px 12px !important;
            box-shadow: none !important;
            background-color: #fff !important;
        }
        .ts-control.focus {
            border-color: #6366f1 !important; /* Indigo-500 */
            box-shadow: 0 0 0 2px #e0e7ff !important; /* Indigo-100 */
        }
        .ts-dropdown {
            border-radius: 0.5rem !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            z-index: 50 !important;
        }
    </style>

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-black text-2xl">Input Analisis Baru</h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li><a class="font-medium text-gray-500 hover:text-blue-600" href="{{ route('dashboard') }}">Dashboard /</a></li>
                <li class="font-medium text-blue-600">Input Analisis</li>
            </ol>
        </nav>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 py-4 px-7 bg-gray-50/50 rounded-t-xl">
            <h3 class="font-semibold text-black text-lg">Formulir Temuan Ketidaklengkapan</h3>
        </div>

        @if(session('success'))
            <div class="mx-7 mt-6 mb-0 rounded-lg bg-green-50 border border-green-200 p-4 text-green-800 shadow-sm flex justify-between items-center" 
                 x-data="{ show: true }" x-show="show">
                <div class="flex items-center gap-3">
                    <div class="bg-green-100 p-2 rounded-full text-green-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <span class="font-bold block">Sukses!</span>
                        <span class="text-sm">{{ session('success') }}</span>
                    </div>
                </div>
                <button @click="show = false" class="text-green-500 hover:text-green-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif
        
        <form action="{{ route('analisis.store') }}" method="POST" class="p-7" x-data="defectHandler()">
            @csrf

            <div class="mb-8">
                <label class="mb-3 block text-sm font-bold text-black">
                    Pilih Berkas Rekam Medis <span class="text-red-500">*</span>
                </label>
                <select id="select-pasien" name="rekam_medis_id" required placeholder="Cari Nama / No RM Pasien...">
                    <option value="">-- Cari Pasien --</option>
                    @foreach($rm_options as $rm)
                        <option value="{{ $rm['value'] }}">{{ $rm['label'] }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-8 p-5 bg-blue-50 rounded-xl border border-blue-100">
                <label class="mb-3 block text-sm font-bold text-black">
                    Tanggal Berkas Diterima / Disetor Unit <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="datetime-local" 
                        name="tgl_berkas_kembali" 
                        value="{{ now()->format('Y-m-d\TH:i') }}"
                        class="w-full rounded-lg border border-blue-300 bg-white py-3 px-4 text-sm font-medium text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition" 
                        required>
                </div>
                <p class="mt-2 text-xs text-blue-600">
                    *Waktu ini digunakan untuk menghitung indikator ketepatan waktu 2x24 jam.
                </p>
            </div>

            <div class="mb-6">
                <label class="mb-3 block text-sm font-bold text-black">Daftar Ketidaklengkapan</label>
                
                <div class="flex flex-col gap-4">
                    <div x-show="defects.length === 0" class="flex flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 py-8 px-4 text-center">
                        <div class="mb-2 rounded-full bg-gray-200 p-3">
                            <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h4 class="text-sm font-bold text-gray-900">Berkas Lengkap</h4>
                        <p class="mt-1 text-xs text-gray-500 mb-4">Tidak ada temuan ketidaklengkapan.</p>
                        
                        <button type="button" @click="addDefect()" class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 py-2 px-5 text-sm font-medium text-white hover:bg-indigo-700 shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah Temuan (Jika Ada)
                        </button>
                    </div>

                    <template x-for="(defect, index) in defects" :key="defect.id">
                        <div class="relative rounded-xl border border-gray-200 bg-gray-50/50 p-5 transition-all hover:shadow-md hover:bg-white hover:border-indigo-200">
                            <button type="button" @click="removeDefect(index)" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition z-10" title="Hapus Baris">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                                
                                <div class="w-full" x-init="initTomSelect($el, index, 'form_id')">
                                    <label class="mb-2 block text-xs font-bold uppercase text-gray-500">1. Nama Formulir</label>
                                    <select :name="`defects[${index}][form_id]`" class="tom-select-form w-full" placeholder="Cari Formulir...">
                                        <option value="">-- Pilih Form --</option>
                                        @foreach($js_form_list as $form)
                                            <option value="{{ $form['value'] }}">{{ $form['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="w-full" x-init="initTomSelect($el, index, 'kriteria_id')">
                                    <label class="mb-2 block text-xs font-bold uppercase text-gray-500">2. Jenis Masalah</label>
                                    <select :name="`defects[${index}][kriteria_id]`" class="tom-select-kriteria w-full" placeholder="Cari Masalah...">
                                        <option value="">-- Pilih Item --</option>
                                        @foreach($js_kriteria_list as $kriteria)
                                            <option value="{{ $kriteria['value'] }}">{{ $kriteria['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-2 block text-xs font-bold uppercase text-gray-500">3. Keterangan</label>
                                    <input type="text" :name="`defects[${index}][note]`" x-model="defect.note" placeholder="Catatan..." 
                                           class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-4 text-sm font-medium outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                </div>
                            </div>
                        </div>
                    </template>

                    <div x-show="defects.length > 0" class="mt-4 text-right" style="display: none;">
                        <button type="button" @click="addDefect()" class="inline-flex items-center justify-center gap-2 rounded-lg border border-indigo-600 py-2 px-5 text-sm font-medium text-indigo-600 hover:bg-indigo-50 transition shadow-sm">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah Baris Lain
                        </button>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end gap-3 border-t border-gray-200 pt-6">
                    <a href="{{ route('dashboard') }}" class="flex justify-center rounded-lg border border-gray-300 bg-white py-2.5 px-6 font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">Batal</a>
                    <button type="submit" class="flex justify-center rounded-lg bg-indigo-600 py-2.5 px-6 font-medium text-white hover:bg-indigo-700 transition shadow-md">Simpan Hasil</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Inisialisasi Tom Select untuk Pasien (Single, di luar Alpine loop)
        new TomSelect("#select-pasien",{
            create: false,
            sortField: { field: "text", direction: "asc" }
        });

        function defectHandler() {
            return {
                defects: [],
                
                // Fungsi Tambah Baris
                addDefect() { 
                    this.defects.push({ 
                        id: Date.now(), // Unique ID untuk :key Alpine agar rendering stabil
                        form_id: '', 
                        kriteria_id: '', 
                        note: '' 
                    }); 
                },
                
                // Fungsi Hapus Baris
                removeDefect(index) { 
                    this.defects.splice(index, 1); 
                },

                // Fungsi Inisialisasi Tom Select di dalam Loop Alpine
                initTomSelect(el, index, fieldName) {
                    // Cari elemen select di dalam div container
                    let selectEl = el.querySelector('select');
                    
                    if(selectEl) {
                        new TomSelect(selectEl, {
                            create: false,
                            sortField: { field: "text", direction: "asc" },
                            onChange: (value) => {
                                // Update model Alpine saat TomSelect berubah
                                this.defects[index][fieldName] = value;
                            }
                        });
                    }
                }
            }
        }
    </script>
</x-app-layout>