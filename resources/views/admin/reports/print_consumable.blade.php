<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Stok ATK</title>
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
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background-color: #e0e0e0;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .danger {
            color: red;
            font-weight: bold;
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

    <div class="no-print" style="text-align: right; padding: 10px; background: #eee;">
        <button onclick="window.print()">🖨️ Cetak</button>
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

    <h3 style="text-align: center;">LAPORAN STOK BARANG HABIS PAKAI (ATK)</h3>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Sisa Stok</th>
                <th>Satuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->code }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->category->name ?? '-' }}</td>
                <td class="text-center {{ $item->quantity <= 5 ? 'danger' : '' }}">{{ $item->quantity }}</td>
                <td class="text-center">{{ $item->unit }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; float: right; text-align: center; width: 200px;">
        <p>Gorontalo, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</p>
        <p>Pengelola Barang,</p>
        <br><br><br>
        <p><strong>( ...................................... )</strong></p>
    </div>
</body>

</html>