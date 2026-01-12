<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Aset Tetap</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            position: relative;
            min-height: 100px;
        }

        .logo {
            position: absolute;
            left: 0;
            top: 0;
            width: 70px;
            height: auto;
        }

        .header-text {
            text-align: center;
            width: 100%;
        }

        .header-text h4 {
            margin: 2px 0;
            font-size: 12pt;
            font-weight: normal;
        }

        .header-text h3 {
            margin: 5px 0;
            font-size: 14pt;
            font-weight: bold;
        }

        .header-text p {
            margin: 1px 0;
            font-size: 9pt;
            font-style: italic;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #e0e0e0;
            text-align: center;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .ttd-area {
            margin-top: 40px;
            float: right;
            text-align: center;
            width: 200px;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="no-print" style="text-align: right; padding: 10px; background: #f0f0f0; border-bottom: 1px solid #ccc;">
        <button onclick="window.print()" style="padding: 5px 15px; cursor: pointer;">🖨️ Cetak Laporan</button>
    </div>

    <div class="header-container">
        <img src="{{ asset('logo.png') }}" class="logo" alt="Logo">
        <div class="header-text">
            <h4>PEMERINTAH PROVINSI GORONTALO</h4>
            <h4>DINAS PENDIDIKAN DAN KEBUDAYAAN</h4>
            <h3>SMK NEGERI 4 GORONTALO</h3>
            <p>Jl. Manado, Kel. Pulubala, Kec. Kota Tengah, Pulubala, Kota Gorontalo, 96127</p>
            <p>Email: smkn4gorontalo@gmail.com / website: smkn4gorontalo.sch.id</p>
        </div>
    </div>

    <h3 style="text-align: center; margin-bottom: 5px;">LAPORAN DATA ASET TETAP</h3>
    <p style="text-align: center; margin-top: 0;">Per Tanggal: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</p>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Lokasi</th>
                <th>Kondisi</th>
                <th>Thn Pengadaan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->code }}</td>
                <td>
                    <b>{{ $item->name }}</b>
                    @if($item->barcode)<br><small>{{ $item->barcode }}</small>@endif
                </td>
                <td>{{ $item->category->name ?? '-' }}</td>
                <td>{{ $item->room->name ?? '-' }}</td>
                <td class="text-center">{{ ucfirst($item->condition) }}</td>
                <td class="text-center">{{ $item->purchase_date ? date('Y', strtotime($item->purchase_date)) : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="ttd-area">
        <p>Gorontalo, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</p>
        <p>Mengetahui,<br>Kepala Sekolah</p>
        <br><br><br>
        <p><strong>( ...................................... )</strong><br>NIP. ..............................</p>
    </div>

</body>

</html>