<!DOCTYPE html>
<html>
<head>
    <title>Laporan Analisis KLPCM</title>
    <style>
        /* RESET & UMUM */
        body { font-family: sans-serif; font-size: 11px; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header h2 { margin: 5px 0; font-size: 12px; color: #555; }

        /* TEKNIK PECAH TABEL: */
        /* Kita buat tabel terpisah-pisah, tapi border collapse agar terlihat menyatu */
        .wrapper-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: -1px; /* Trik agar garis antar tabel tidak dobel */
        }
        
        .wrapper-table th, .wrapper-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
            text-align: left;
        }

        /* HEADER TABEL (Khusus Judul Kolom) */
        .header-row th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
            vertical-align: middle;
            height: 30px;
        }

        /* PERINTAH SAKTI: Mencegah tabel pasien terpotong */
        .patient-block {
            page-break-inside: avoid !important;
        }

        /* PENGATURAN LEBAR KOLOM (WAJIB FIX AGAR LURUS) */
        .col-no { width: 4%; text-align: center; }
        .col-rm { width: 10%; }
        .col-pasien { width: 18%; }
        
        /* Kondisional Lebar (PHP logic nanti di-inject class) */
        .col-form { width: 20%; } 
        .col-kat  { width: 10%; text-align: center; }
        .col-item { width: 20%; }
        .col-ket  { width: 18%; }

        /* Utility */
        .badge { padding: 2px 5px; border-radius: 3px; font-size: 9px; font-weight: bold; display: inline-block; white-space: nowrap; }
        .badge-manual { background-color: #fef3c7; color: #92400e; border: 1px solid #fcd34d; } 
        .badge-electronic { background-color: #f3e8ff; color: #6b21a8; border: 1px solid #d8b4fe; }
        .text-center { text-align: center !important; }
        .text-green { color: green; }
        .text-red { color: red; }
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
        $count_manual = 0; $count_elektronik = 0; $total_item_masalah = 0;
        if($jenis == 'umum') {
            foreach($data as $row) {
                foreach($row->detail_analisis as $detail) {
                    $total_item_masalah++;
                    if($detail->formulir->kategori == 'manual') $count_manual++; else $count_elektronik++;
                }
            }
        }
        // Atur lebar kolom dinamis biar rapi
        $w_form = $jenis == 'umum' ? '20%' : '25%';
        $w_item = $jenis == 'umum' ? '20%' : '25%';
    @endphp

    <div style="margin-bottom: 20px;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 50%; border: none; padding: 0;">
                    <table style="width: 95%; border: 1px solid #ddd; border-collapse: collapse;">
                        <tr><th colspan="2" style="background-color: #e0e7ff; border:1px solid #999; padding:4px;">Statistik Berkas</th></tr>
                        <tr><td style="border:1px solid #ddd; padding:4px;">Total Berkas</td><td class="text-center" style="border:1px solid #ddd;">{{ $total }}</td></tr>
                        <tr><td style="border:1px solid #ddd; padding:4px;">Berkas Lengkap</td><td class="text-center text-green" style="border:1px solid #ddd;">{{ $lengkap }}</td></tr>
                        <tr><td style="border:1px solid #ddd; padding:4px;">Tidak Lengkap</td><td class="text-center text-red" style="border:1px solid #ddd;">{{ $tidak_lengkap }}</td></tr>
                        <tr><td style="border:1px solid #ddd; padding:4px;">Persentase</td><td class="text-center font-bold" style="border:1px solid #ddd;">{{ $persentase }}%</td></tr>
                    </table>
                </td>
                @if($jenis == 'umum')
                <td style="width: 50%; border: none; padding: 0;">
                    <table style="width: 95%; float: right; border: 1px solid #ddd; border-collapse: collapse;">
                        <tr><th colspan="2" style="background-color: #fce7f3; border:1px solid #999; padding:4px;">Item Ketidaklengkapan</th></tr>
                        <tr><td style="border:1px solid #ddd; padding:4px;">Total Item</td><td class="text-center" style="border:1px solid #ddd;">{{ $total_item_masalah }}</td></tr>
                        <tr><td style="border:1px solid #ddd; padding:4px;">Manual</td><td class="text-center" style="border:1px solid #ddd;"><span class="badge badge-manual">{{ $count_manual }}</span></td></tr>
                        <tr><td style="border:1px solid #ddd; padding:4px;">Elektronik</td><td class="text-center" style="border:1px solid #ddd;"><span class="badge badge-electronic">{{ $count_elektronik }}</span></td></tr>
                    </table>
                </td>
                @endif
            </tr>
        </table>
    </div>

    <table class="wrapper-table header-row">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-rm">Tgl & RM</th>
                <th class="col-pasien">Pasien & Dokter</th>
                <th style="width: {{ $w_form }}">Nama Formulir</th>
                @if($jenis == 'umum')
                    <th class="col-kat">Kategori</th>
                @endif
                <th style="width: {{ $w_item }}">Masalah (Item)</th>
                <th class="col-ket">Keterangan</th>
            </tr>
        </thead>
    </table>

    @php $no = 1; @endphp
    @foreach($data as $row)
        
        <table class="wrapper-table patient-block">
            <tbody>
                @if($row->detail_analisis->count() > 0)
                    @foreach($row->detail_analisis as $i => $detail)
                    <tr>
                        @if($i == 0)
                            <td rowspan="{{ $row->detail_analisis->count() }}" class="col-no text-center">
                                {{ $no++ }}
                            </td>
                            <td rowspan="{{ $row->detail_analisis->count() }}" class="col-rm">
                                {{ \Carbon\Carbon::parse($row->tgl_analisis)->format('d/m/y') }}<br>
                                <b>{{ $row->rekam_medis->no_rm }}</b>
                            </td>
                            <td rowspan="{{ $row->detail_analisis->count() }}" class="col-pasien">
                                <b>{{ $row->rekam_medis->pasien->nama }}</b><br>
                                <span style="color:#555; font-size:10px;">{{ $row->rekam_medis->ruangan->nama }}</span><br>
                                <small>dr. {{ $row->rekam_medis->dokter->nama }}</small>
                            </td>
                        @endif

                        <td style="width: {{ $w_form }}">{{ $detail->formulir->nama }}</td>
                        
                        @if($jenis == 'umum')
                            <td class="col-kat">
                                @if($detail->formulir->kategori == 'manual')
                                    <span class="badge badge-manual">MANUAL</span>
                                @else
                                    <span class="badge badge-electronic">E-RME</span>
                                @endif
                            </td>
                        @endif

                        <td style="width: {{ $w_item }}">
                            @if($detail->kriteria)
                                <b>{{ $detail->kriteria->item }}</b><br>
                                <small style="color:#666; font-style:italic;">{{ $detail->kriteria->kategori }}</small>
                            @else
                                <span style="color:red">-</span>
                            @endif
                        </td>
                        <td class="col-ket">{{ $detail->catatan ?? '-' }}</td>
                    </tr>
                    @endforeach

                @else
                    <tr>
                        <td class="col-no text-center">{{ $no++ }}</td>
                        <td class="col-rm">
                            {{ \Carbon\Carbon::parse($row->tgl_analisis)->format('d/m/y') }}<br>
                            <b>{{ $row->rekam_medis->no_rm }}</b>
                        </td>
                        <td class="col-pasien">
                            <b>{{ $row->rekam_medis->pasien->nama }}</b><br>
                            <span style="color:#555; font-size:10px;">{{ $row->rekam_medis->ruangan->nama }}</span><br>
                            <small>dr. {{ $row->rekam_medis->dokter->nama }}</small>
                        </td>
                        <td colspan="{{ $jenis == 'umum' ? 4 : 3 }}" class="text-center" style="padding: 10px; color: green; font-weight: bold; background-color: #f0fdf4;">
                            BERKAS LENGKAP
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

    @endforeach

    <div class="footer">
        <p>Dicetak oleh: {{ Auth::user()->name }} pada {{ now()->format('d/m/Y H:i') }}</p>
    </div>

</body>
</html>