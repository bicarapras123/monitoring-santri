<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pencairan Dana Cash</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800 p-8" onload="window.print()">
    <div class="max-w-2xl mx-auto border border-gray-300 p-8 rounded-2xl shadow-sm">
        <div class="text-center border-b pb-4 mb-6">
            <h1 class="text-xl font-bold uppercase">Formulir Pengajuan Pencairan Dana Cash</h1>
            <p class="text-xs text-gray-500 mt-1">Bank Sampah RW.04</p>
        </div>

        <div class="space-y-3 text-sm mb-8">
            <div class="grid grid-cols-3">
                <span class="font-semibold">NIK</span>
                <span class="col-span-2">: {{ $nasabah->nik }}</span>
            </div>
            <div class="grid grid-cols-3">
                <span class="font-semibold">Nama Lengkap</span>
                <span class="col-span-2">: {{ $nasabah->nama_lengkap }}</span>
            </div>
            <div class="grid grid-cols-3">
                <span class="font-semibold">Jenis Kelamin</span>
                <span class="col-span-2">: {{ $nasabah->jenis_kelamin }}</span>
            </div>
            <div class="grid grid-cols-3">
                <span class="font-semibold">Tempat, Tgl Lahir</span>
                <span class="col-span-2">: {{ $nasabah->tempat_lahir }}, {{ $nasabah->tanggal_lahir }}</span>
            </div>
            <div class="grid grid-cols-3">
                <span class="font-semibold">Nomor Telepon</span>
                <span class="col-span-2">: {{ $nasabah->nomor_telepon }}</span>
            </div>
            <div class="grid grid-cols-3">
                <span class="font-semibold">Nama Orang Tua</span>
                <span class="col-span-2">: {{ $nasabah->nama_orang_tua }}</span>
            </div>
            <div class="grid grid-cols-3">
                <span class="font-semibold">Alamat Lengkap</span>
                <span class="col-span-2">: {{ $nasabah->alamat_lengkap }}</span>
            </div>
        </div>

        <div class="text-xs text-gray-600 mb-12 border-t pt-4">
            <p>Saya menyatakan dengan sesungguhnya bahwa seluruh data diri di atas adalah benar dan sesuai dengan keadaan sebenarnya. Pengajuan pencairan dana cash ini dilakukan atas persetujuan yang sah.</p>
        </div>

        <div class="flex justify-between text-center text-sm pt-8">
            <div>
                <p class="mb-16">Petugas Bank Sampah,</p>
                <p class="font-bold underline">( .................................... )</p>
            </div>
            <div>
                <p class="mb-16">Nasabah,</p>
                <p class="font-bold underline">( {{ $nasabah->nama_lengkap }} )</p>
            </div>
        </div>
    </div>
</body>
</html>