<!DOCTYPE html>
<html>
<head>
    <title>Laporan Analisis KLPCM</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header h2 { margin: 5px 0; font-size: 12px; color: #555; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { border: 1px solid #000; padding: 6px; text-align: left; vertical-align: top; }
        table th { background-color: #f0f0f0; text-align: center; font-weight: bold; }
        
        .badge { padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: bold; display: inline-block; }
        .badge-manual { background-color: #fef3c7; color: #92400e; border: 1px solid #fcd34d; } 
        .badge-electronic { background-color: #f3e8ff; color: #6b21a8; border: 1px solid #d8b4fe; }
        
        .footer { margin-top: 30px; text-align: right; font-size: 10px; color: #777; }
        
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .text-red { color: red; }
        .text-green { color: green; }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN ANALISIS KELENGKAPAN REKAM MEDIS (KLPCM)</h1>
        @if($judul_tambahan)
            <h3 style="margin:5px 0; font-size:13px;">{{ $judul_tambahan }}</h3>
        @endif
        <h2>Periode: {{ \Carbon\Carbon::parse($tgl_awal)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($tgl_akhir)->format('d/m/Y') }}</h2>
    </div>

    @php
        $count_manual = 0;
        $count_elektronik = 0;
        $total_item_masalah = 0;

        if($jenis == 'umum') {
            foreach($data as $row) {
                foreach($row->detail_analisis as $detail) {
                    $total_item_masalah++;
                    if($detail->formulir->kategori == 'manual') {
                        $count_manual++;
                    } else {
                        $count_elektronik++;
                    }
                }
            }
        }
    @endphp

    <div style="margin-bottom: 20px;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 50%; border: none; padding: 0;">
                    <table style="width: 90%;">
                        <tr>
                            <th colspan="2" style="background-color: #e0e7ff;">Statistik Berkas</th>
                        </tr>
                        <tr>
                            <td>Total Berkas Dianalisis</td>
                            <td class="text-center">{{ $total }}</td>
                        </tr>
                        <tr>
                            <td>Berkas Lengkap</td>
                            <td class="text-center text-green">{{ $lengkap }}</td>
                        </tr>
                        <tr>
                            <td>Tidak Lengkap (KLPCM)</td>
                            <td class="text-center text-red">{{ $tidak_lengkap }}</td>
                        </tr>
                        <tr>
                            <td>Persentase Kelengkapan</td>
                            <td class="text-center font-bold">{{ $persentase }}%</td>
                        </tr>
                    </table>
                </td>

                @if($jenis == 'umum')
                <td style="width: 50%; border: none; padding: 0;">
                    <table style="width: 90%; float: right;">
                        <tr>
                            <th colspan="2" style="background-color: #fce7f3;">Statistik Item Ketidaklengkapan</th>
                        </tr>
                        <tr>
                            <td>Total Item Masalah</td>
                            <td class="text-center">{{ $total_item_masalah }}</td>
                        </tr>
                        <tr>
                            <td>Kategori Manual (Kertas)</td>
                            <td class="text-center"><span class="badge badge-manual">{{ $count_manual }} Item</span></td>
                        </tr>
                        <tr>
                            <td>Kategori Elektronik (E-RME)</td>
                            <td class="text-center"><span class="badge badge-electronic">{{ $count_elektronik }} Item</span></td>
                        </tr>
                    </table>
                </td>
                @else
                <td style="width: 50%; border: none;"></td>
                @endif
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="10%">Tgl & RM</th>
                <th width="18%">Pasien & Dokter</th>
                
                <th width="{{ $jenis == 'umum' ? '20%' : '25%' }}">Nama Formulir</th>
                
                @if($jenis == 'umum')
                    <th width="10%">Kategori</th>
                @endif
                
                <th width="{{ $jenis == 'umum' ? '20%' : '25%' }}">Masalah (Item)</th>
                <th width="18%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($data as $row)
                
                @if($row->detail_analisis->count() > 0)
                    @foreach($row->detail_analisis as $i => $detail)
                    <tr>
                        @if($i == 0)
                            <td rowspan="{{ $row->detail_analisis->count() }}" class="text-center">{{ $no++ }}</td>
                            <td rowspan="{{ $row->detail_analisis->count() }}">
                                {{ \Carbon\Carbon::parse($row->tgl_analisis)->format('d/m/y') }}<br>
                                <b>{{ $row->rekam_medis->no_rm }}</b>
                            </td>
                            <td rowspan="{{ $row->detail_analisis->count() }}">
                                <b>{{ $row->rekam_medis->pasien->nama }}</b><br>
                                <span style="color:#555; font-size:10px;">{{ $row->rekam_medis->ruangan->nama }}</span><br>
                                <small>dr. {{ $row->rekam_medis->dokter->nama }}</small>
                            </td>
                        @endif

                        <td>{{ $detail->formulir->nama }}</td>
                        
                        @if($jenis == 'umum')
                            <td class="text-center">
                                @if($detail->formulir->kategori == 'manual')
                                    <span class="badge badge-manual">MANUAL</span>
                                @else
                                    <span class="badge badge-electronic">E-RME</span>
                                @endif
                            </td>
                        @endif

                            <td style="vertical-align: top;">
                            @if($detail->kriteria)
                                {{-- Baris 1: Nama Item (Tebal) --}}
                                <div style="font-weight: bold; margin-bottom: 2px;">
                                    {{ $detail->kriteria->item }}
                                </div>
                                
                                {{-- Baris 2: Kategori (Kecil, Miring, Abu-abu) --}}
                                <div style="font-size: 9px; color: #666; font-style: italic;">
                                    {{ $detail->kriteria->kategori }}
                                </div>
                            @else
                                {{-- Jika data master terhapus --}}
                                <span style="color:red; font-size:10px;">-</span>
                            @endif
                        </td>
                            <td>{{  $detail->catatan ?? '-' }}</td>
                    </tr>
                    @endforeach

                @else
                    {{-- JIKA BERKAS LENGKAP --}}
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($row->tgl_analisis)->format('d/m/y') }}<br>
                            <b>{{ $row->rekam_medis->no_rm }}</b>
                        </td>
                        <td>
                            <b>{{ $row->rekam_medis->pasien->nama }}</b><br>
                            <span style="color:#555; font-size:10px;">{{ $row->rekam_medis->ruangan->nama }}</span><br>
                            <small>dr. {{ $row->rekam_medis->dokter->nama }}</small>
                        </td>
                        <td colspan="{{ $jenis == 'umum' ? 4 : 3 }}" class="text-center" style="padding: 15px; color: green; font-weight: bold;">
                            BERKAS LENGKAP
                        </td>
                    </tr>
                @endif

            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak oleh: {{ Auth::user()->name }} pada {{ now()->format('d/m/Y H:i') }}</p>
    </div>

</body>
</html>