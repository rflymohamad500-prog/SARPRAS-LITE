<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
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
            background-color: #f2f2f2;
        }

        .masuk {
            color: green;
            font-weight: bold;
        }

        .keluar {
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

    <div class="no-print" style="text-align: right; padding: 10px;">
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

    <h3 style="text-align:center; margin:0;">LAPORAN TRANSAKSI BARANG</h3>
    <p style="text-align:center; margin:5px 0;">
        Periode: {{ \Carbon\Carbon::parse($startDate)->locale('id')->isoFormat('D MMMM Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->locale('id')->isoFormat('D MMMM Y') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Nama Barang</th>
                <th>Jml</th>
                <th>Petugas</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $trx)
            <tr>
                <td style="text-align:center">{{ \Carbon\Carbon::parse($trx->date)->locale('id')->isoFormat('D MMMM Y') }}</td>
                <td style="text-align:center">
                    @if($trx->type == 'in') <span class="masuk">MASUK</span>
                    @else <span class="keluar">KELUAR</span> @endif
                </td>
                <td>{{ $trx->item->name ?? 'Item Dihapus' }}</td>
                <td style="text-align:center">{{ $trx->amount }}</td>
                <td>{{ $trx->user->name ?? '-' }}</td>
                <td>{{ $trx->notes }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>