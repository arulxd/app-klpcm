<x-app-layout>
    <style>
        .ts-control {
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            padding: 10px 12px !important;
            box-shadow: none !important;
            background-color: #fff !important;
            min-height: 45px;
        }
        .ts-control.focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 2px #e0e7ff !important;
        }
        .ts-dropdown { z-index: 50 !important; }
        .ts-control input { font-size: 0.875rem !important; }
    </style>

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-black text-2xl">Revisi Analisis</h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li><a class="font-medium text-gray-500 hover:text-blue-600" href="{{ route('dashboard') }}">Dashboard /</a></li>
                <li class="font-medium text-blue-600">Edit</li>
            </ol>
        </nav>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 py-5 px-7 bg-gray-50/50 rounded-t-xl">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xl border border-indigo-200">
                    {{ substr($analisis->rekam_medis->pasien->nama, 0, 1) }}
                </div>
                <div>
                    <span class="block text-xs font-bold uppercase text-gray-500 tracking-wide">Pasien Sedang Direvisi</span>
                    <h3 class="font-bold text-lg text-gray-900">
                        {{ $analisis->rekam_medis->pasien->nama }} 
                        <span class="font-normal text-gray-500 text-base">({{ $analisis->rekam_medis->pasien->no_rm }})</span>
                    </h3>
                </div>
            </div>
        </div>

        <form action="{{ route('analisis.update', $analisis->id) }}" method="POST" class="p-7" x-data="defectHandler()">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label class="mb-4 block text-sm font-bold text-black">
                    Daftar Revisi Ketidaklengkapan
                </label>
                
                <div class="flex flex-col gap-4">
                    <template x-if="defects.length === 0">
                        <div class="flex flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 py-10 px-4 text-center">
                            <div class="mb-3 rounded-full bg-green-100 p-3">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <h4 class="text-sm font-bold text-gray-900">Semua Temuan Sudah Dihapus</h4>
                            <p class="mt-1 text-xs text-gray-500 mb-5">Klik "Update Perubahan" untuk menyimpan status menjadi LENGKAP.</p>
                            <button type="button" @click="addDefect()" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 hover:underline">
                                + Tambah Temuan Baru
                            </button>
                        </div>
                    </template>

                    <template x-for="(defect, index) in defects" :key="defect.unique_id">
                        <div class="relative rounded-xl border border-gray-200 bg-gray-50/50 p-5 transition-all hover:shadow-md hover:border-indigo-200 hover:bg-white">
                            
                            <button type="button" @click="removeDefect(index)" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition z-10">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                                <input type="hidden" :name="`defects[${index}][id]`" :value="defect.id">

                                <div class="w-full" x-init="initTomSelect($el, index, 'form_id', defect.form_id)">
                                    <label class="mb-2 block text-xs font-bold uppercase text-gray-500">1. Nama Formulir</label>
                                    <select :name="`defects[${index}][form_id]`" class="w-full" placeholder="Cari Form...">
                                        <option value="">Pilih Form...</option>
                                        @foreach($js_form_list as $f)
                                            <option value="{{ $f['value'] }}">{{ $f['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="w-full" x-init="initTomSelect($el, index, 'kriteria_id', defect.kriteria_id)">
                                    <label class="mb-2 block text-xs font-bold uppercase text-gray-500">2. Jenis Masalah</label>
                                    <select :name="`defects[${index}][kriteria_id]`" class="w-full" placeholder="Cari Masalah...">
                                        <option value="">Pilih Item...</option>
                                        @foreach($js_kriteria_list as $k)
                                            <option value="{{ $k['value'] }}">{{ $k['label'] }}</option>
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
                </div>

                <div x-show="defects.length > 0" class="mt-4 text-right">
                    <button type="button" @click="addDefect()" class="inline-flex items-center justify-center gap-2 rounded-lg border border-indigo-600 py-2 px-5 text-sm font-medium text-indigo-600 hover:bg-indigo-50 transition shadow-sm">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Baris Lain
                    </button>
                </div>
            </div>

            <div class="mt-10 flex items-center justify-end gap-4 border-t border-gray-200 pt-6">
                <a href="{{ route('dashboard') }}" class="flex justify-center rounded-lg border border-gray-300 bg-white py-2.5 px-6 font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">
                    Batal
                </a>
                <button type="submit" class="flex justify-center rounded-lg bg-indigo-600 py-2.5 px-6 font-medium text-white hover:bg-indigo-700 transition shadow-md hover:shadow-lg">
                    Update Perubahan
                </button>
            </div>
        </form>
    </div>

    <script>
        function defectHandler() {
            return {
                // Load data existing langsung dari variabel yang dikirim Controller
                defects: @json($js_existing_defects).map(item => ({
                    ...item,
                    unique_id: Math.random()
                })),

                addDefect() {
                    this.defects.push({ 
                        unique_id: Math.random(),
                        id: null, 
                        form_id: '', 
                        kriteria_id: '', 
                        note: '' 
                    });
                },
                
                removeDefect(index) {
                    this.defects.splice(index, 1);
                },

                initTomSelect(el, index, fieldName, initialValue) {
                    let selectEl = el.querySelector('select');
                    
                    if(selectEl && !selectEl.tomselect) {
                        let ts = new TomSelect(selectEl, {
                            create: false,
                            sortField: { field: "text", direction: "asc" },
                            placeholder: selectEl.getAttribute('placeholder'),
                            onChange: (value) => {
                                this.defects[index][fieldName] = value;
                            }
                        });

                        if (initialValue) {
                            ts.setValue(initialValue);
                        }
                    }
                }
            }
        }
    </script>
</x-app-layout>