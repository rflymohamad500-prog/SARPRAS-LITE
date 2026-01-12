<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Berita Acara 1 Lembar</title>
    <style>
        /* TAMPILAN UNTUK LAYAR BIASA (INPUT DATA) */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }

        .container-input {
            background: white;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        input {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 20px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
        }

        button.print-btn {
            background-color: #28a745;
            margin-left: 10px;
        }

        button:hover {
            opacity: 0.9;
        }

        /* TABEL RIWAYAT DI LAYAR */
        .history-section {
            margin-top: 30px;
        }

        table.history {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.history th,
        table.history td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        /* --- BAGIAN PENTING: PENGATURAN CETAK (PRINT) --- */

        /* Sembunyikan area print saat di layar biasa */
        #print-area {
            display: none;
        }

        @media print {

            /* 1. Reset halaman browser */
            @page {
                size: A4;
                margin: 0;
            }

            body {
                margin: 0;
                padding: 0;
                background: white;
            }

            /* 2. Sembunyikan form input saat print */
            .container-input {
                display: none !important;
            }

            /* 3. Tampilkan surat & Atur Layout 1 Lembar Penuh */
            #print-area {
                display: flex !important;
                flex-direction: column;
                height: 297mm;
                width: 210mm;
                padding: 20mm;
                box-sizing: border-box;
                position: relative;
            }

            /* 4. Bagian Konten (Atas) */
            .print-content {
                flex-grow: 1;
            }

            /* 5. Bagian Tanda Tangan (Bawah) */
            .print-footer {
                margin-bottom: 20px;
            }

            /* --- PENGATURAN KOP SURAT DENGAN LOGO --- */
            .kop-surat {
                position: relative;
                /* Wajib ada agar logo bisa diatur posisinya */
                text-align: center;
                border-bottom: 3px solid black;
                padding-bottom: 10px;
                margin-bottom: 20px;
                min-height: 100px;
                /* Pastikan tinggi cukup untuk logo */
            }

            /* Pengaturan Gambar Logo */
            .logo-sekolah {
                position: absolute;
                /* Tempel bebas di pojok */
                left: 0;
                /* Tempel di kiri */
                top: 0;
                /* Tempel di atas */
                width: 80px;
                /* Ukuran logo (sesuaikan keinginan) */
                height: auto;
            }

            /* Agar teks tidak terlalu mepet logo (opsional, karena logo absolut tidak mendorong teks) */
            .kop-text {
                width: 100%;
            }

            .kop-surat h3 {
                margin: 0;
                font-size: 18px;
                font-weight: bold;
            }

            .kop-surat h4 {
                margin: 0;
                font-size: 14px;
                font-weight: normal;
            }

            .kop-surat p {
                margin: 0;
                font-size: 12px;
            }

            .judul {
                text-align: center;
                margin-bottom: 30px;
            }

            .judul h3 {
                text-decoration: underline;
                margin: 0;
                font-size: 16px;
            }

            .judul p {
                margin: 5px 0 0 0;
                font-size: 14px;
            }

            .text-body {
                font-family: 'Times New Roman', serif;
                font-size: 12pt;
                line-height: 1.5;
            }

            table.surat {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }

            table.surat th,
            table.surat td {
                border: 2px solid black;
                padding: 10px;
                text-align: center;
                font-family: 'Times New Roman', serif;
            }

            table.surat th {
                font-weight: bold;
                background-color: #fff;
            }

            .ttd-wrapper {
                display: flex;
                justify-content: space-between;
                font-family: 'Times New Roman', serif;
                margin-top: 20px;
            }

            .ttd-box {
                text-align: center;
                width: 40%;
            }

            .ttd-space {
                height: 80px;
            }
        }
    </style>
</head>

<body>

    <div class="container-input">
        <h2>Aplikasi Cetak Mutasi Barang (1 Lembar)</h2>
        <label>Nomor Surat:</label>
        <input type="text" id="inpNoSurat" value="BA/MUTASI/2026/4">

        <label>Kode Barang:</label>
        <input type="text" id="inpKode" value="INV-20260008">

        <label>Nama Barang:</label>
        <input type="text" id="inpNama" value="Laptop">

        <label>Lokasi Awal:</label>
        <input type="text" id="inpAwal" value="Perpustakaan">

        <label>Lokasi Baru (Tujuan):</label>
        <input type="text" id="inpBaru" value="Ruangan Kepsek">

        <button onclick="simpanData()">Simpan ke Riwayat</button>
        <button class="print-btn" onclick="cetakSurat()">Print Surat</button>

        <div class="history-section">
            <h3>Riwayat Disimpan</h3>
            <table class="history">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Barang</th>
                        <th>Tujuan</th>
                    </tr>
                </thead>
                <tbody id="tabelRiwayat"></tbody>
            </table>
        </div>
    </div>

    <div id="print-area">

        <div class="print-content">
            <div class="kop-surat">
                <img src="{{ asset('img/logo.png') }}" alt="Logo Sekolah" class="logo-sekolah">

                <div class="kop-text">
                    <h4>PEMERINTAH PROVINSI GORONTALO</h4>
                    <h4>DINAS PENDIDIKAN DAN KEBUDAYAAN</h4>
                    <h3>SMK NEGERI 4 GORONTALO</h3>
                    <p>Jl. Manado, Kel. Pulubala, Kec. Kota Tengah, Pulubala, Kota Gorontalo, 96127</p>
                    <p>Email: smkn4gorontalo@gmail.com/website: smkn4gorontalo.sch.id</p>
                </div>
            </div>

            <div class="judul">
                <h3>BERITA ACARA MUTASI BARANG</h3>
                <p>Nomor: <span id="outNoSurat"></span></p>
            </div>

            <div class="text-body">
                <p>Pada hari ini, <b id="outTanggal"></b>, kami yang bertanda tangan di bawah ini:</p>
                <p>Telah melakukan serah terima / pemindahan lokasi aset tetap milik sekolah dengan rincian sebagai berikut:</p>

                <table class="surat">
                    <thead>
                        <tr>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Lokasi Awal</th>
                            <th>Lokasi Baru (Tujuan)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td id="outKode"></td>
                            <td id="outNama"></td>
                            <td id="outAwal"></td>
                            <td style="font-weight: bold;" id="outBaru"></td>
                        </tr>
                    </tbody>
                </table>

                <p>Demikian berita acara ini dibuat dengan sesungguhnya untuk dipergunakan sebagaimana mestinya.</p>
            </div>
        </div>

        <div class="print-footer">
            <div class="ttd-wrapper">
                <div class="ttd-box">
                    <p>Yang Menyerahkan<br>(Admin/Petugas)</p>
                    <div class="ttd-space"></div>
                    <p><b>Administrator</b></p>
                </div>
                <div class="ttd-box">
                    <p>Mengetahui,<br>Kepala Sarana Prasarana</p>
                    <div class="ttd-space"></div>
                    <p>( ........................................ )</p>
                    <p style="text-align: left; margin-left: 20px;">NIP. ........................................</p>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Set tanggal otomatis saat web dibuka
        window.onload = function() {
            loadRiwayat();
            updateTanggal();
        };

        function updateTanggal() {
            const date = new Date();
            // Format: Thursday, 8 January 2026 (Inggris sesuai gambar)
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            // KODE BARU (Bahasa Indonesia)
            document.getElementById('outTanggal').innerText = date.toLocaleDateString('id-ID', options);
        }

        function cetakSurat() {
            // Pindahkan data dari input ke surat
            document.getElementById('outNoSurat').innerText = document.getElementById('inpNoSurat').value;
            document.getElementById('outKode').innerText = document.getElementById('inpKode').value;
            document.getElementById('outNama').innerText = document.getElementById('inpNama').value;
            document.getElementById('outAwal').innerText = document.getElementById('inpAwal').value;
            document.getElementById('outBaru').innerText = document.getElementById('inpBaru').value;

            // Perintah print browser
            window.print();
        }

        function simpanData() {
            const data = {
                tgl: new Date().toLocaleDateString('id-ID'),
                barang: document.getElementById('inpNama').value,
                tujuan: document.getElementById('inpBaru').value
            };

            let history = JSON.parse(localStorage.getItem('mutasi_db')) || [];
            history.push(data);
            localStorage.setItem('mutasi_db', JSON.stringify(history));

            loadRiwayat();
            alert("Data tersimpan di riwayat browser!");
        }

        function loadRiwayat() {
            const history = JSON.parse(localStorage.getItem('mutasi_db')) || [];
            const tbody = document.getElementById('tabelRiwayat');
            tbody.innerHTML = "";

            history.slice().reverse().forEach((item, index) => {
                tbody.innerHTML += `<tr>
                    <td>${index + 1}</td>
                    <td>${item.tgl}</td>
                    <td>${item.barang}</td>
                    <td>${item.tujuan}</td>
                </tr>`;
            });
        }
    </script>
</body>

</html>