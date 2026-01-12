<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Label Aset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }

        /* Layout Kertas A4 */
        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 10mm;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            /* 3 Kolom Stiker */
            grid-gap: 10px;
            align-content: start;
        }

        /* Desain Kotak Stiker */
        .sticker {
            border: 2px solid #000;
            height: 110px;
            padding: 5px;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            background: #fff;
            overflow: hidden;
        }

        /* QR Code */
        .sticker-qr {
            width: 75px;
            height: 75px;
            flex-shrink: 0;
            margin-right: 8px;
        }

        .sticker-qr img {
            width: 100%;
            height: 100%;
        }

        /* Teks Info */
        .sticker-info {
            flex-grow: 1;
            overflow: hidden;
        }

        .school-name {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 2px solid #000;
            display: inline-block;
            margin-bottom: 3px;
            padding-bottom: 1px;
        }

        .item-name {
            font-size: 11px;
            font-weight: bold;
            margin: 2px 0;
            line-height: 1.1;
            height: 24px;
            overflow: hidden;
        }

        .item-code {
            font-size: 13px;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            margin-top: 2px;
            background: #000;
            color: #fff;
            padding: 1px 3px;
            display: inline-block;
        }

        .item-room {
            font-size: 9px;
            margin-top: 3px;
            font-style: italic;
        }

        /* Tombol Cetak */
        .no-print {
            text-align: center;
            margin-bottom: 20px;
        }

        .btn-print {
            background: #333;
            color: #fff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }

        @media print {
            body {
                background: none;
                margin: 0;
                padding: 0;
            }

            .page {
                width: auto;
                height: auto;
                margin: 0;
                padding: 0;
                border: none;
                box-shadow: none;
            }

            .no-print {
                display: none;
            }

            .sticker {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            @page {
                margin: 10mm;
            }
        }
    </style>
</head>

<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">🖨️ Cetak Label Sekarang</button>
        <p style="margin-top:5px; font-size:12px;">Potong sesuai garis kotak, lalu tempel pada aset.</p>
    </div>

    <div class="page">
        @foreach($items as $item)
        <div class="sticker">
            <div class="sticker-qr">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $item->code }}" alt="QR">
            </div>
            <div class="sticker-info">
                <div class="school-name">SMKN 4 GORONTALO</div>
                <div class="item-name">{{ $item->name }}</div>
                <div class="item-code">{{ $item->code }}</div>
                <div class="item-room">{{ $item->room->name ?? '-' }}</div>
            </div>
        </div>
        @endforeach
    </div>

</body>

</html>