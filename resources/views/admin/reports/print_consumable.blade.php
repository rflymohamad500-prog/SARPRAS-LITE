<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Barang Habis Pakai</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        /* KOP SURAT (SAMA PERSIS DENGAN ASET) */
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

        /* TABEL */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background-color: #e0e0e0;
            text-align: center;
            font-weight: bold;
        }

        /* FOTO */
        .img-print {
            width: 35px;
            height: 35px;
            object-fit: cover;
            border: 1px solid #ccc;
            border-radius: 3px;
            display: block;
            margin: auto;
        }

        /* UTILITY */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .no-pic {
            font-size: 9px;
            color: #888;
            text-align: center;
            display: block;
        }

        .stok-aman {
            color: green;
            font-weight: bold;
        }

        .stok-tipis {
            color: orange;
            font-weight: bold;
        }

        .stok-habis {
            color: red;
            font-weight: bold;
        }

        /* TANDA TANGAN */
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
        <button onclick="window.print()" style="padding: 5px 15px; cursor: pointer; font-weight:bold;">🖨️ Cetak Laporan</button>
    </div>

    <div class="header-container">
        <img src="{{ asset('logo.png') }}" class="logo" alt="Logo" onerror="this.style.display='none'">
        <div class="header-text">
            <h4>PEMERINTAH PROVINSI GORONTALO</h4>
            <h4>DINAS PENDIDIKAN DAN KEBUDAYAAN</h4>
            <h3>SMK NEGERI 4 GORONTALO</h3>
            <p>Jl. Manado, Kel. Pulubala, Kec. Kota Tengah, Pulubala, Kota Gorontalo, 96127</p>
            <p>Email: smkn4gorontalo@gmail.com / website: smkn4gorontalo.sch.id</p>
        </div>
    </div>

    <div style="text-align: center; margin-bottom: 20px;">
        <h3 style="margin: 0;">LAPORAN STOK BARANG HABIS PAKAI</h3>
        <p style="margin: 5px 0;">(ATK / Bahan Praktik / Kebersihan)</p>
        <p style="margin: 0;">Per Tanggal: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="6%">Foto</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Lokasi</th>
                <th width="10%">Sisa Stok</th>
                <th width="8%">Satuan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>

                <td>
                    @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" class="img-print" alt="Foto">
                    @else
                    <span class="no-pic">-No Pic-</span>
                    @endif
                </td>

                <td>{{ $item->code }}</td>
                <td><b>{{ $item->name }}</b></td>
                <td>{{ $item->category->name ?? '-' }}</td>
                <td>{{ $item->room->name ?? '-' }}</td>

                <td class="text-center font-bold" style="font-size: 1.1em;">
                    {{ $item->quantity }}
                </td>

                <td class="text-center">{{ $item->unit }}</td>

                <td class="text-center">
                    @if($item->quantity == 0)
                    <span class="stok-habis">HABIS</span>
                    @elseif($item->quantity < 5)
                        <span class="stok-tipis">MENIPIS</span>
                        @else
                        <span class="stok-aman">TERSEDIA</span>
                        @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f9f9f9;">
                <td colspan="6" class="text-right font-bold">TOTAL ITEM</td>
                <td class="text-center font-bold">{{ $items->count() }} Jenis</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <div class="ttd-area">
        <p>Gorontalo, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</p>
        <p>Mengetahui,<br>Kepala Sarana Prasarana</p>
        <br><br><br>
        <p><strong>( ...................................... )</strong><br>NIP. ..............................</p>
    </div>

</body>

</html>