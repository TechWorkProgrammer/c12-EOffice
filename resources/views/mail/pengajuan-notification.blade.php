<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Surat Keluar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
        }

        .header p {
            margin: 2px 0;
        }

        .content {
            margin: 20px;
        }

        .signature {
            margin-top: 30px;
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>TENTARA NASIONAL INDONESIA</h1>
        <p>MARKAS BESAR ANGKATAN DARAT</p>
        {{-- <p>Alamat: [Alamat Dinas]</p>
        <p>Telepon: [Nomor Telepon]</p>
        <p>Email: [Email Resmi Dinas]</p> --}}
    </div>

    <div class="content">
        {{-- <p>{{ $suratMasuk->tanggal_surat }}</p> --}}

        {{-- <p><strong>Nomor</strong>: {{ $suratMasuk->nomor_surat }}</p>
        <p><strong>Klasifikasi</strong>: {{ $suratMasuk->klasifikasiSurat->name }}</p>
        <p><strong>Perihal</strong>: {{ $suratMasuk->perihal }}</p> --}}

        <p><strong>Kepada Yth.</strong></p>
        <p>Bpk. {{ $pengajuan->penerima->name }}<br>
        <p>{{ $pengajuan->penerima->pejabat->name }}<br>
        <p>Markas Besar Angkatan Darat</p>
        <p>Tentara Nasional Indonesia</p>
        <br>
        <p><strong>Dengan hormat,</strong></p>

        <p>Bersama email ini, saya bermaksud memohon izin untuk mengirimkan draft surat {{ $pengajuan->perihal }} kepada
            Bapak/Ibu untuk ditinjau dan ditandatangani. Untuk detail informasi draft surat bisa diakses lebih lanjut di link berikut:</p>
        <p>https://sparti.online/letters/out/detail?uuid={{ $pengajuan->uuid }}</p>
        {{-- <ul>
            <li><strong>Hari/Tanggal</strong>: [Hari dan tanggal pelaksanaan]</li>
            <li><strong>Waktu</strong>: [Jam pelaksanaan]</li>
            <li><strong>Tempat</strong>: [Lokasi kegiatan]</li>
        </ul> --}}

        {{-- <p>Kegiatan tersebut bertujuan untuk [deskripsi singkat tujuan kegiatan], yang tentunya membutuhkan dukungan
            dari berbagai pihak. Oleh karena itu, kami bermaksud memohon kepada Kementerian Keuangan untuk dapat
            memberikan <strong>dukungan dana</strong> guna kelancaran kegiatan tersebut.</p> --}}

        {{-- <p>Sebagai bahan pertimbangan, kami lampirkan proposal kegiatan yang memuat rincian rencana kegiatan dan anggaran yang dibutuhkan.</p> --}}
        <br>
        <p>Atas perhatian dan dukungan yang diberikan, kami ucapkan terima kasih.</p>
        <br>
        <div class="">
            <p>Hormat kami,</p>
            <p>{{ $pengajuan->draft->creator->name }}</p>
            {{-- <p>[NIP]</p> --}}
            <p>{{ $pengajuan->draft->creator->role }}</p>
            <p>Markas Besar Angkatan Darat</p>
            <p>Tentara Nasional Indonesia</p>
        </div>
    </div>

    {{-- <div class="footer">
        <p><strong>Lampiran:</strong></p>
        <ol>
            <li>Proposal Kegiatan <strong>[Nama Kegiatan]</strong></li>
            <li>[Lampiran lain jika ada]</li>
        </ol>
    </div> --}}

</body>

</html>
