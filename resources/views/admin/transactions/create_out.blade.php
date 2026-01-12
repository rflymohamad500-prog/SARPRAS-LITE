@extends('layouts.admin')

@section('title', 'Barang Keluar')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-danger">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-sign-out-alt"></i> Input Barang Keluar
                </h6>
            </div>
            <div class="card-body">

                <div class="card bg-light mb-3 border-left-danger">
                    <div class="card-body p-3">
                        <label class="font-weight-bold text-danger">
                            <i class="fas fa-barcode"></i> SCANNER ALAT (USB)
                        </label>
                        <div class="input-group">
                            <input type="text" id="scanner_input" class="form-control font-weight-bold"
                                placeholder="Klik sini lalu tembak barcode..." autofocus autocomplete="off"
                                style="font-size: 1.2rem; letter-spacing: 1px;">

                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="button" onclick="resetScanner()">
                                    <i class="fas fa-sync"></i> Reset
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">Otomatis mendeteksi saat scan selesai.</small>
                    </div>
                </div>

                <div id="camera-area" class="mb-3 d-none">
                    <div class="card border-danger">
                        <div class="card-header bg-danger text-white py-1">
                            <span class="small"><i class="fas fa-camera"></i> Kamera Aktif</span>
                            <button type="button" class="close text-white" onclick="stopCamera()">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="card-body p-0 text-center">
                            <div id="reader" style="width: 100%; max-width: 100%; margin: auto;"></div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.transactions.store_out') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="font-weight-bold text-success">BARANG TERDETEKSI (Otomatis):</label>
                        <input type="text" id="nama_barang_auto" class="form-control form-control-lg font-weight-bold text-dark bg-light"
                            readonly placeholder="--- Belum ada barang discan ---"
                            style="border: 2px solid #28a745;">
                    </div>

                    <hr>

                    <div class="form-group">
                        <label class="font-weight-bold">Pilih Manual (Opsional)</label>
                        <div class="input-group">
                            <select name="item_id" id="item_id" class="form-control select2" required>
                                <option value="">-- Pilih Manual / Scan --</option>
                                @foreach($items as $item)
                                <option value="{{ $item->id }}"
                                    data-code="{{ $item->code }}"
                                    data-barcode="{{ $item->barcode }}"
                                    data-unit="{{ $item->unit }}"
                                    data-stock="{{ $item->quantity }}"
                                    data-name="{{ $item->name }}">
                                    {{ $item->code }} - {{ $item->name }} (Sisa: {{ $item->quantity }})
                                </option>
                                @endforeach
                            </select>

                            <div class="input-group-append">
                                <button class="btn btn-danger" type="button" onclick="startCamera()">
                                    <i class="fas fa-camera"></i> Kamera
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Jumlah</label>
                                <div class="input-group">
                                    <input type="number" name="amount" id="amount" class="form-control" min="1" value="1" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-light" id="unit_display">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Tanggal</label>
                                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Keterangan</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Keperluan..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-danger btn-block btn-lg">PROSES KELUAR</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // --- 1. INISIALISASI ---
    $(document).ready(function() {
        // Cek apakah script jalan
        console.log("Script Barang Keluar READY!");

        // Fokus ke scanner USB
        $('#scanner_input').focus();

        // Inisialisasi Select2 jika ada
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        }
    });

    // --- 2. LOGIKA UTAMA: CARI BARANG ---
    function cariBarangByBarcode(barcode) {
        let found = false;
        let code = barcode ? barcode.toString().trim() : '';

        if (code.length < 3) return; // Abaikan jika terlalu pendek

        console.log("Mencari Barcode:", code); // Debug di Console

        // Loop semua opsi dropdown
        $('#item_id option').each(function() {
            let dbBarcode = $(this).data('barcode');
            let dbCode = $(this).data('code');

            if (dbBarcode) dbBarcode = dbBarcode.toString().trim();
            if (dbCode) dbCode = dbCode.toString().trim();

            // BANDINGKAN
            if (dbBarcode === code || dbCode === code) {

                // 1. Pilih Item
                $('#item_id').val($(this).val()).trigger('change');

                // 2. Update Tampilan Nama
                let nama = $(this).data('name');
                let stok = $(this).data('stock');

                $('#nama_barang_auto').val("✅ " + nama + " (Sisa: " + stok + ")");
                $('#nama_barang_auto').css({
                    'background-color': '#28a745',
                    'color': 'white'
                });

                // 3. Suara / Notif (Opsional)
                // alert("Ketemu: " + nama); 

                // Reset input scanner
                $('#scanner_input').val('');

                found = true;
                return false; // Stop Loop
            }
        });

        if (!found) {
            $('#nama_barang_auto').val("❌ TIDAK DITEMUKAN: " + code);
            $('#nama_barang_auto').css({
                'background-color': '#dc3545',
                'color': 'white'
            });
        }
    }

    // --- 3. EVENT LISTENER SCANNER USB (AUTO DETECT) ---
    let typingTimer;
    let doneTypingInterval = 200; // Jeda 0.2 detik setelah scan selesai

    $('#scanner_input').on('input', function() {
        clearTimeout(typingTimer);
        let val = $(this).val();

        // Tunggu sebentar, jika tidak ada ketikan baru, proses!
        if (val.length > 0) {
            typingTimer = setTimeout(function() {
                cariBarangByBarcode(val);
            }, doneTypingInterval);
        }
    });

    // Jaga-jaga jika scanner kirim ENTER
    $('#scanner_input').on('keypress', function(e) {
        if (e.which == 13) {
            e.preventDefault();
            clearTimeout(typingTimer);
            cariBarangByBarcode($(this).val());
        }
    });

    function resetScanner() {
        $('#scanner_input').val('').focus();
        $('#nama_barang_auto').val('').css({
            'background-color': '#f8f9fa',
            'color': 'black'
        });
    }

    // --- 4. EVENT SAAT MANUAL PILIH DROPDOWN ---
    $('#item_id').on('change', function() {
        let opt = $(this).find(':selected');
        let nama = opt.data('name');

        // Update Info Lainnya
        $('#unit_display').text(opt.data('unit') || '-');

        // Update Kolom Hijau
        if (nama) {
            $('#nama_barang_auto').val("✅ " + nama + " (Sisa: " + opt.data('stock') + ")");
            $('#nama_barang_auto').css({
                'background-color': '#28a745',
                'color': 'white'
            });
        }
    });

    // --- 5. LOGIKA KAMERA HP ---
    let html5QrcodeScanner = null;
    window.startCamera = function() {
        $('#camera-area').removeClass('d-none');
        html5QrcodeScanner = new Html5QrcodeScanner("reader", {
            fps: 10,
            qrbox: {
                width: 250,
                height: 250
            }
        }, false);
        html5QrcodeScanner.render((decodedText) => {
            stopCamera();
            cariBarangByBarcode(decodedText);
        }, (error) => {});
    }
    window.stopCamera = function() {
        if (html5QrcodeScanner) html5QrcodeScanner.clear().then(() => {
            $('#camera-area').addClass('d-none');
        });
        else $('#camera-area').addClass('d-none');
    }
</script>
@endsection