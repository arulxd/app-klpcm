<!DOCTYPE html>
<html>
<head>
    <title>Rekapitulasi KLPCM Per Ruangan</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header h2 { margin: 5px 0; font-size: 14px; color: #555; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { border: 1px solid #000; padding: 10px; text-align: left; }
        table th { background-color: #f0f0f0; text-align: center; font-weight: bold; }
        
        .total-row td { font-weight: bold; background-color: #f9f9f9; }
        .footer { margin-top: 30px; text-align: right; font-size: 11px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>REKAPITULASI JUMLAH KETIDAKLENGKAPAN (KLPCM)</h1>
        <h3 style="margin:5px 0;">BERDASARKAN RUANGAN RAWAT INAP</h3>
        <h2>Periode: {{ \Carbon\Carbon::parse($tgl_awal)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($tgl_akhir)->format('d/m/Y') }}</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Nama Ruangan / Unit</th>
                <th width="20%">Total Dianalisis</th> <th width="20%">Tidak Lengkap</th>
                <th width="20%">Persentase (KLPCM)</th> </tr>
        </thead>
        <tbody>
            @php 
                $grand_total_analisis = 0;
                $grand_total_tidak = 0;
            @endphp

            @foreach($data as $index => $row)
            @php
                // Hitung Persentase (Cegah error pembagian nol)
                $persen = 0;
                if ($row->jumlah_total_analisis > 0) {
                    $persen = round(($row->jumlah_tidak_lengkap / $row->jumlah_total_analisis) * 100, 2);
                }

                // Tambahkan ke Grand Total
                $grand_total_analisis += $row->jumlah_total_analisis;
                $grand_total_tidak += $row->jumlah_tidak_lengkap;
            @endphp
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $row->nama }}</td>
                
                <td style="text-align: center;">
                    {{ $row->jumlah_total_analisis }} Berkas
                </td>

                <td style="text-align: center; color: red;">
                    {{ $row->jumlah_tidak_lengkap }} Berkas
                </td>

                <td style="text-align: center; font-weight: bold;">
                    {{ $persen }}%
                </td>
            </tr>
            @endforeach
            
            @php
                $total_persen = 0;
                if ($grand_total_analisis > 0) {
                    $total_persen = round(($grand_total_tidak / $grand_total_analisis) * 100, 2);
                }
            @endphp
            <tr class="total-row">
                <td colspan="2" style="text-align: right; padding-right: 20px;">TOTAL KESELURUHAN</td>
                <td style="text-align: center;">{{ $grand_total_analisis }}</td>
                <td style="text-align: center;">{{ $grand_total_tidak }}</td>
                <td style="text-align: center;">{{ $total_persen }}%</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak oleh: {{ Auth::user()->name }} pada {{ now()->format('d/m/Y H:i') }}</p>
    </div>

</body>
</html>