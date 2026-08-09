<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Resmi Rekapitulasi Bank Sampah RW</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; font-size: 11px; line-height: 1.4; margin: 0; padding: 20px; }
        
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px double #333; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #111; font-size: 16px; text-transform: uppercase; letter-spacing: 0.5px; }
        .header p { margin: 4px 0 0; color: #555; font-size: 10px; }
        
        .cards { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .cards td { width: 33.33%; padding: 8px; background: #fdfdfd; border: 1px solid #ccc; text-align: center; }
        .cards .title { font-size: 9px; font-weight: bold; color: #666; text-transform: uppercase; }
        .cards .value { font-size: 13px; font-weight: bold; margin-top: 4px; color: #111; }

        .section-title { font-size: 12px; font-weight: bold; margin: 15px 0 6px; border-left: 3px solid #059669; padding-left: 6px; text-transform: uppercase; color: #222; }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 4px; margin-bottom: 10px; }
        table.data-table th, table.data-table td { border: 1px solid #bbb; padding: 5px 7px; text-align: left; font-size: 10px; }
        table.data-table th { background-color: #f1f5f9; font-weight: bold; color: #1e293b; }
        
        /* CSS Visual Bar Chart untuk PDF */
        .chart-container { margin-top: 8px; margin-bottom: 12px; border: 1px solid #cbd5e1; padding: 10px; background: #f8fafc; border-radius: 4px; }
        .bar-row { margin-bottom: 6px; font-size: 10px; }
        .bar-label { display: inline-block; width: 110px; font-weight: bold; color: #334155; }
        .bar-track { display: inline-block; width: 60%; background: #e2e8f0; height: 12px; border-radius: 3px; vertical-align: middle; overflow: hidden; }
        .bar-fill { background: #10b981; height: 100%; border-radius: 3px; }
        .bar-value { display: inline-block; margin-left: 8px; font-weight: bold; color: #047857; }

        .evaluation-box { background: #f8fafc; border: 1px solid #e2e8f0; padding: 8px 10px; border-radius: 4px; margin-top: 6px; font-size: 10px; color: #475569; }
        .evaluation-box strong { color: #1e293b; }

        .footer-container { margin-top: 30px; width: 100%; page-break-inside: avoid; }
        .signature-table { width: 100%; border-collapse: collapse; }
        .signature-table td { width: 50%; text-align: center; font-size: 11px; vertical-align: top; }
        .signature-space { height: 55px; }
        
        .print-info { margin-top: 20px; text-align: right; font-size: 9px; color: #777; border-top: 1px solid #eee; padding-top: 5px; }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h2>Laporan Resmi Rekapitulasi Bank Sampah RW</h2>
        <p>Dokumen Akuntabilitas Operasional, Statistik Lingkungan, dan Keuangan Warga</p>
    </div>

    <!-- Ringkasan Eksekutif -->
    <table class="cards">
        <tr>
            <td>
                <div class="title">Total Akumulasi Sampah</div>
                <div class="value" style="color: #059669;">{{ number_format($totalSampah, 1) }} Kg</div>
            </td>
            <td>
                <div class="title">Total Frekuensi Transaksi</div>
                <div class="value" style="color: #4F46E5;">{{ $totalTransaksi }} Kali</div>
            </td>
            <td>
                <div class="title">Total Partisipasi Nasabah</div>
                <div class="value" style="color: #7C3AED;">{{ $totalNasabah }} Orang</div>
            </td>
        </tr>
    </table>

    <!-- 1. Rincian Jenis Sampah -->
    <div class="section-title">1. Rincian Berat Berdasarkan Jenis Sampah</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 35px; text-align: center;">No</th>
                <th>Kategori Jenis Sampah</th>
                <th style="width: 120px;">Total Berat Terkumpul</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jenisSampah as $index => $js)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $js->jenis_sampah }}</td>
                <td><strong>{{ number_format($js->berat, 1) }} Kg</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- 2. Statistik Bulanan (Dilengkapi Visual Bar Chart) -->
    <div class="section-title">2. Rekapitulasi Statistik Berat Sampah Per Bulan</div>
    <div class="chart-container">
        <div style="font-weight: bold; margin-bottom: 6px; font-size: 10px; color: #475569;">Visualisasi Grafik Batang Bulanan:</div>
        @php 
            $maxBeratBulan = $grafikData->max('total_berat') > 0 ? $grafikData->max('total_berat') : 1; 
        @endphp
        @forelse($grafikData as $gd)
            @php $persen = ($gd->total_berat / $maxBeratBulan) * 100; @endphp
            <div class="bar-row">
                <span class="bar-label">{{ $gd->bulan }}</span>
                <div class="bar-track">
                    <div class="bar-fill" style="width: {{ $persen }}%;"></div>
                </div>
                <span class="bar-value">{{ number_format($gd->total_berat, 1) }} Kg</span>
            </div>
        @empty
            <p style="text-align: center; color: #666; margin: 5px 0;">Belum ada data grafik bulanan.</p>
        @endforelse
    </div>

    <!-- 3. Statistik Harian (Senin - Minggu) -->
    <div class="section-title">3. Tren Aktivitas Penyetoran Harian (Senin s/d Minggu)</div>
    <div class="chart-container">
        <div style="font-weight: bold; margin-bottom: 6px; font-size: 10px; color: #475569;">Visualisasi Grafik Harian:</div>
        @php 
            $maxBeratHari = $grafikHarian->max('total_berat') > 0 ? $grafikHarian->max('total_berat') : 1; 
        @endphp
        @forelse($grafikHarian as $gh)
            @php $persenHari = ($gh->total_berat / $maxBeratHari) * 100; @endphp
            <div class="bar-row">
                <span class="bar-label">{{ $gh->nama_hari }}</span>
                <div class="bar-track">
                    <div class="bar-fill" style="width: {{ $persenHari }}%; background: #6366f1;"></div>
                </div>
                <span class="bar-value" style="color: #4f46e5;">{{ number_format($gh->total_berat, 1) }} Kg</span>
            </div>
        @empty
            <p style="text-align: center; color: #666; margin: 5px 0;">Belum ada data aktivitas harian minggu ini.</p>
        @endforelse
    </div>
    <div class="evaluation-box">
        <strong>Analisis Evaluasi Pengurus:</strong> Grafik harian merekam fluktuasi partisipasi warga sepanjang pekan. Lonjakan volume setoran dapat dimanfaatkan untuk mengoptimalkan penjadwalan petugas piket kebersihan lingkungan RW.
    </div>

    <!-- 4. Metode Pencairan Uang -->
    <div class="section-title">4. Rekapitulasi Keuangan Berdasarkan Metode Pencairan</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 35px; text-align: center;">No</th>
                <th>Metode Pencairan Saldo</th>
                <th style="width: 150px;">Total Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pieChartData as $index => $pc)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ ucfirst($pc->metode_pencairan) }}</td>
                <td>Rp {{ number_format($pc->total_uang, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align: center; color: #666;">Belum ada data transaksi pencairan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- 5. Riwayat Rekap Laporan -->
    <div class="section-title">5. Riwayat Unggah Dokumen Rekap Laporan</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Jenis Laporan</th>
                <th>Tujuan Instansi/Pengurus</th>
                <th style="width: 130px;">Tanggal Unggah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $item)
            <tr>
                <td><strong>{{ $item->jenis_laporan }}</strong></td>
                <td>{{ $item->tujuan }}</td>
                <td>{{ $item->created_at->format('d M Y, H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align: center; color: #666;">Belum ada dokumen laporan yang diunggah.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tanda Tangan Pengesahan -->
    <div class="footer-container">
        <table class="signature-table">
            <tr>
                <td>
                    <p>Mengetahui,<br><strong>Ketua RW</strong></p>
                    <div class="signature-space"></div>
                    <p>___________________________</p>
                </td>
                <td>
                    <p>Dibuat Oleh,<br><strong>Pengurus Bank Sampah RW</strong></p>
                    <div class="signature-space"></div>
                    <p>___________________________</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="print-info">
        <p>Dokumen Laporan Resmi Dicetak Otomatis Melalui Sistem Informasi Bank Sampah pada: {{ date('d-m-Y H:i') }} WIB</p>
    </div>

</body>
</html>