<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Resmi - Penarikan Saldo Bank Sampah</title>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 13px; color: #000; line-height: 1.6; margin: 40px; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 22px; text-transform: uppercase; }
        .content-info { margin-bottom: 25px; }
        
        /* Gaya Tabel Formal */
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #000; padding: 10px; text-align: left; }
        th { background-color: #f8f8f8; text-align: center; font-weight: bold; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        /* Gaya Teks Formal */
        .paragraph { margin-bottom: 15px; text-align: justify; }
        .report-meta { margin-bottom: 20px; border-left: 4px solid #000; padding-left: 15px; }
        
        .signature-area { margin-top: 50px; }
        .signature-box { width: 250px; text-align: center; float: right; }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h1>BANK SAMPAH RW.04</h1>
        <p>RW.04 Kelurahan Cengkareng Barat, Jakarta Barat</p>
    </div>

    <div class="content-info">
        <h3 class="text-center">LAPORAN AKTIVITAS PENARIKAN SALDO NASABAH</h3>
        
        <div class="report-meta">
            <p><strong>Nomor Dokumen:</strong> BSW-04/LAP/{{ date('Y/m') }}/{{ str_pad(rand(1,999), 3, '0', STR_PAD_LEFT) }}<br>
               <strong>Perihal:</strong> Laporan Realisasi Penarikan Saldo Nasabah<br>
               <strong>Periode Pelaporan:</strong> {{ $startDate ?? 'Awal Operasional' }} s/d {{ $endDate ?? date('d/m/Y') }}</p>
        </div>

        <p class="paragraph">
            Melalui dokumen ini, pengurus Bank Sampah RW.04 menyampaikan laporan komprehensif mengenai realisasi penarikan saldo oleh nasabah. 
            Laporan ini disusun berdasarkan data transaksi yang tercatat dalam sistem internal kami untuk periode yang telah ditentukan di atas. 
            Adapun total penarikan yang berhasil diproses pada periode ini mencapai <strong>Rp {{ number_format($totalNilaiPenarikan, 0, ',', '.') }}</strong> 
            yang mencakup seluruh metode pencairan meliputi Transfer Bank dan E-Wallet.
        </p>

        <p class="paragraph">
            Berikut adalah rincian data transaksi penarikan saldo secara detail:
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Nasabah</th>
                <th>Metode</th>
                <th>Nominal (Rp)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayatTransaksi as $index => $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}</td>
                <td>{{ $item->user->nasabah->nama_lengkap ?? $item->user->name ?? '-' }}</td>
                <td>{{ $item->metode_pencairan ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($item->jumlah_penarikan ?? 0, 0, ',', '.') }}</td>
                <td class="text-center">{{ ucfirst($item->status) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">Tidak ada transaksi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">TOTAL NILAI PENARIKAN</th>
                <th class="text-right">Rp {{ number_format($totalNilaiPenarikan, 0, ',', '.') }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    <div class="content-info">
        <p class="paragraph">
            Demikian laporan ini kami sampaikan agar dapat ditelaah dan dipergunakan sebagaimana mestinya sebagai bahan evaluasi kegiatan operasional Bank Sampah RW.04. 
            Besar harapan kami agar sistem pencairan saldo ini dapat terus ditingkatkan demi kenyamanan nasabah dan tertib administrasi organisasi.
        </p>
    </div>

    <div class="signature-area">
        <div class="signature-box">
            <p>Jakarta, {{ date('d F Y') }}</p>
            <p>Admin Bank Sampah RW.04</p>
            <br><br><br>
            <p><strong>( ______________________ )</strong></p>
        </div>
    </div>

</body>
</html>