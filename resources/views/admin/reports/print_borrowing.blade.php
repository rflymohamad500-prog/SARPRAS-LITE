<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman</title>
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
        }

        th {
            background-color: #d1ecf1;
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

    <h3 style="text-align:center; margin:0;">LAPORAN PEMINJAMAN BARANG</h3>
    <p style="text-align:center; margin:5px 0;">
        Periode: {{ \Carbon\Carbon::parse($startDate)->locale('id')->isoFormat('D MMMM Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->locale('id')->isoFormat('D MMMM Y') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Tgl Pinjam</th>
                <th>Nama Peminjam</th>
                <th>Barang</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th>Tgl Kembali</th>
            </tr>
        </thead>
        <tbody>
            @forelse($borrowings as $b)
            <tr>
                <td style="text-align:center">{{ \Carbon\Carbon::parse($b->borrow_date)->locale('id')->isoFormat('D MMM Y') }}</td>
                <td>{{ $b->borrower_name }}</td>
                <td>{{ $b->item->name ?? '-' }}</td>
                <td>{{ $b->location }}</td>
                <td style="text-align:center">
                    @if($b->status == 'returned') Kembali @else <b>Dipinjam</b> @endif
                </td>
                <td style="text-align:center">
                    {{ $b->return_date_actual ? \Carbon\Carbon::parse($b->return_date_actual)->locale('id')->isoFormat('D MMM Y') : '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center">Tidak ada data peminjaman di periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; float: right; text-align: center; width: 200px;">
        <p>Gorontalo, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</p>
        <p>Petugas Peminjaman,</p>
        <br><br><br>
        <p><strong>( ...................................... )</strong></p>
    </div>
</body>

</html>