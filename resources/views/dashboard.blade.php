<x-app-layout>
    <x-slot name="header">Dashboard Overview</x-slot>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4 mb-6">
        
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md transition">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-50 mb-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h4 class="text-2xl font-bold text-black mb-1">{{ $total_berkas }}</h4>
            <span class="text-sm font-medium text-gray-500">Total Berkas Masuk</span>
        </div>
        
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md transition">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-50 mb-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h4 class="text-2xl font-bold text-black mb-1">{{ $total_lengkap }}</h4>
            <span class="text-sm font-medium text-gray-500">Berkas Lengkap</span>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md transition md:col-span-2 relative overflow-hidden">
             <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-50 mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                 <h4 class="text-2xl font-bold text-black mb-1">{{ $total_revisi }}</h4>
                 <span class="text-sm font-medium text-gray-500">Perlu Revisi Dokter</span>
            </div>
             <div class="hidden xl:flex gap-2 items-end h-24 opacity-50 absolute right-6 bottom-6">
                 <div class="w-3 bg-red-200 rounded-t h-10"></div>
                 <div class="w-3 bg-red-300 rounded-t h-16"></div>
                 <div class="w-3 bg-red-500 rounded-t h-24"></div>
                 <div class="w-3 bg-red-300 rounded-t h-12"></div>
                 <div class="w-3 bg-red-200 rounded-t h-8"></div>
             </div>
        </div>
    </div>
    <div class="mb-6">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-xl font-bold text-black">Performa Kelengkapan Ruangan</h3>
                <span class="text-sm text-gray-500">Real-time Hari Ini</span>
            </div>

            @if(count($per_ruangan) > 0)
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4">
                    @foreach($per_ruangan as $nama_ruang => $stat)
                        @php
                            // Tentukan Warna Berdasarkan Persentase
                            $colorClass = 'bg-red-500'; // Default Merah (< 50%)
                            $textClass = 'text-red-600';
                            $bgClass = 'bg-red-50';
                            
                            if($stat['persen'] == 100) {
                                $colorClass = 'bg-green-500';
                                $textClass = 'text-green-600';
                                $bgClass = 'bg-green-50';
                            } elseif ($stat['persen'] >= 80) {
                                $colorClass = 'bg-blue-500';
                                $textClass = 'text-blue-600';
                                $bgClass = 'bg-blue-50';
                            } elseif ($stat['persen'] >= 50) {
                                $colorClass = 'bg-yellow-500';
                                $textClass = 'text-yellow-600';
                                $bgClass = 'bg-yellow-50';
                            }
                        @endphp

                        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:shadow-md transition">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h5 class="font-bold text-black text-lg truncate w-40" title="{{ $nama_ruang }}">
                                        {{ $nama_ruang }}
                                    </h5>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $stat['lengkap'] }} dari {{ $stat['total'] }} Berkas
                                    </p>
                                </div>
                                <div class="{{ $bgClass }} {{ $textClass }} px-2 py-1 rounded-lg text-xs font-bold">
                                    {{ $stat['persen'] }}%
                                </div>
                            </div>

                            <div class="w-full bg-gray-100 rounded-full h-2.5 mb-1">
                                <div class="{{ $colorClass }} h-2.5 rounded-full transition-all duration-1000" style="width: {{ $stat['persen'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-6 text-center">
                    <p class="text-gray-500 text-sm">Belum ada data analisis ruangan untuk hari ini.</p>
                </div>
            @endif
        </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:gap-6">
        
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="border-b border-gray-100 pb-4 mb-4">
                <h4 class="text-lg font-bold text-black">Persentase Kelengkapan</h4>
            </div>
            <div id="chartOne" class="flex justify-center"></div> 
            
            <div class="flex justify-center gap-8 mt-6">
                <div class="flex items-center gap-2">
                    <span class="block h-3 w-3 rounded-full bg-green-500"></span>
                    <span class="text-sm font-medium text-black">Lengkap</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="block h-3 w-3 rounded-full bg-red-500"></span>
                    <span class="text-sm font-medium text-black">Revisi</span>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="border-b border-gray-100 pb-4 mb-4 flex justify-between items-center">
                <h4 class="text-lg font-bold text-black">Top 5 Dokter "Tidak Lengkap"</h4>
                <span class="text-xs font-medium bg-gray-100 text-gray-500 py-1 px-2 rounded">Evaluasi</span>
            </div>
            
            @if(count($chart_bar_names) > 0)
                <div id="chartTwo"></div>
            @else
                <div class="flex h-64 items-center justify-center text-gray-400 italic flex-col gap-2">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Semua berkas dokter lengkap!</span>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // --- KONFIGURASI CHART 1 (DONUT) ---
            const chartOneOptions = {
                series: @json($chart_pie), // Mengambil Data dari Controller
                chart: {
                    type: 'donut',
                    height: 320,
                    fontFamily: 'Inter, sans-serif',
                },
                labels: ['Lengkap', 'Revisi'],
                colors: ['#10B981', '#EF4444'], // Hijau, Merah
                legend: { show: false }, 
                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    fontSize: '16px',
                                    fontWeight: 600,
                                    color: '#64748B',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => {
                                            return a + b
                                        }, 0)
                                    }
                                },
                                value: {
                                    fontSize: '24px',
                                    fontWeight: 700,
                                    color: '#1E293B',
                                }
                            }
                        }
                    }
                },
                dataLabels: { enabled: false },
                tooltip: { 
                    enabled: true,
                    style: { fontSize: '14px' }
                },
                stroke: { width: 0 }
            };

            const chartOne = new ApexCharts(document.querySelector("#chartOne"), chartOneOptions);
            chartOne.render();


            // --- KONFIGURASI CHART 2 (BAR) ---
            // Cek apakah ada data dokter bermasalah
            const barNames = @json($chart_bar_names);
            
            if(barNames.length > 0) {
                const chartTwoOptions = {
                    series: [{
                        name: 'Jumlah Revisi',
                        data: @json($chart_bar_counts) // Data Jumlah
                    }],
                    chart: {
                        type: 'bar',
                        height: 300,
                        toolbar: { show: false },
                        fontFamily: 'Inter, sans-serif',
                    },
                    colors: ['#3B82F6'], // Biru
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            horizontal: true, 
                            barHeight: '40%',
                            distributed: false
                        }
                    },
                    dataLabels: { 
                        enabled: true,
                        textAnchor: 'start',
                        style: {
                            colors: ['#fff'],
                            fontSize: '12px',
                        },
                        offsetX: 0
                    },
                    xaxis: {
                        categories: barNames, // Data Nama Dokter
                        labels: {
                            style: { colors: '#64748B', fontSize: '12px' }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: { colors: '#1E293B', fontSize: '13px', fontWeight: 500 },
                            maxWidth: 150
                        }
                    },
                    grid: {
                        strokeDashArray: 4,
                        yaxis: { lines: { show: false } },
                        padding: { top: 0, right: 0, bottom: 0, left: 10 }
                    },
                    tooltip: { theme: 'light' }
                };

                if(document.querySelector("#chartTwo")) {
                    const chartTwo = new ApexCharts(document.querySelector("#chartTwo"), chartTwoOptions);
                    chartTwo.render();
                }
            }
        });
    </script>
</x-app-layout>