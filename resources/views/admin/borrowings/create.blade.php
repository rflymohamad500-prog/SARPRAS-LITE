@extends('layouts.admin')

@section('title', 'Form Peminjaman Barang')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-hand-holding"></i> Form Peminjaman Aset
                </h6>
            </div>
            <div class="card-body">

                <div class="card bg-light mb-3 border-left-primary">
                    <div class="card-body p-3">
                        <label class="font-weight-bold text-primary">
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
                        <small class="text-muted">Otomatis mendeteksi barang yang akan dipinjam.</small>
                    </div>
                </div>

                <div id="camera-area" class="mb-3 d-none">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white py-1">
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

                <form action="{{ route('admin.borrowings.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="font-weight-bold text-success">BARANG TERDETEKSI (Otomatis):</label>
                        <input type="text" id="nama_barang_auto" class="form-control form-control-lg font-weight-bold text-dark bg-light"
                            readonly placeholder="--- Belum ada barang discan ---"
                            style="border: 2px solid #4e73df;">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Lokasi Asal Barang</label>
                                <input type="text" id="origin_room" class="form-control bg-light" readonly placeholder="-">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sisa Stok Tersedia</label>
                                <input type="text" id="stock_display" class="form-control bg-light" readonly placeholder="0">
                            </div>
                        </div>
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
                                    data-name="{{ $item->name }}"
                                    data-stock="{{ $item->quantity }}"
                                    data-unit="{{ $item->unit ?? 'Unit' }}"
                                    data-room="{{ $item->room->name ?? '-' }}">
                                    {{ $item->code }} - {{ $item->name }} (Asal: {{ $item->room->name ?? '-' }})
                                </option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" onclick="startCamera()">
                                    <i class="fas fa-camera"></i> Kamera
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-danger">Dipinjam Untuk Ruangan Mana? *</label>
                        <select name="location" class="form-control select2" required>
                            <option value="">-- Pilih Ruangan Tujuan --</option>
                            @if(isset($rooms))
                            @foreach($rooms as $room)
                            <option value="{{ $room->name }}">{{ $room->name }}</option>
                            @endforeach
                            @endif
                            <option value="Luar Sekolah">Luar Sekolah / Lainnya</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Nama Peminjam</label>
                        <input type="text" name="borrower_name" class="form-control" required placeholder="Contoh: Budi Santoso">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Pinjam</label>
                                <input type="date" name="borrow_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Rencana Kembali</label>
                                <input type="date" name="return_date_plan" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Pinjam</label>
                        <input type="number" name="amount" id="amount" class="form-control" value="1" min="1" required>
                    </div>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Keperluan..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                        <i class="fas fa-save"></i> Simpan Peminjaman
                    </button>
                    <a href="{{ route('admin.borrowings.index') }}" class="btn btn-secondary btn-block">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // Fokus ke scanner USB
        $('#scanner_input').focus();

        // Inisialisasi Select2
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        }
    });

    // --- LOGIKA UTAMA: CARI BARANG ---
    function cariBarangByBarcode(barcode) {
        let found = false;
        let code = barcode ? barcode.toString().trim() : '';

        if (code.length < 3) return;

        // Loop dropdown
        $('#item_id option').each(function() {
            let dbBarcode = $(this).data('barcode');
            let dbCode = $(this).data('code');

            if (dbBarcode) dbBarcode = dbBarcode.toString().trim();
            if (dbCode) dbCode = dbCode.toString().trim();

            if (dbBarcode === code || dbCode === code) {
                // KETEMU!
                $('#item_id').val($(this).val()).trigger('change');

                let nama = $(this).data('name');
                let stok = $(this).data('stock');
                let asal = $(this).data('room');

                // Tampilan Biru (Primary) - Sukses
                $('#nama_barang_auto').val("✅ " + nama);
                $('#nama_barang_auto').css({
                    'background-color': '#4e73df',
                    'color': 'white'
                });

                // Info Tambahan
                $('#origin_room').val(asal);
                $('#stock_display').val(stok + " " + ($(this).data('unit') || 'Unit'));

                // Reset input scanner & Pindah fokus ke Peminjam (atau Jumlah)
                $('#scanner_input').val('');
                // Fokus ke nama peminjam agar alur cepat
                $('input[name="borrower_name"]').focus();

                found = true;
                return false;
            }
        });

        if (!found) {
            $('#nama_barang_auto').val("❌ TIDAK DITEMUKAN: " + code);
            $('#nama_barang_auto').css({
                'background-color': '#e74a3b',
                'color': 'white'
            });

            // Reset info lain
            $('#origin_room').val('-');
            $('#stock_display').val('0');
        }
    }

    // --- AUTO DETECT SCANNER USB ---
    let typingTimer;
    let doneTypingInterval = 200;

    $('#scanner_input').on('input', function() {
        clearTimeout(typingTimer);
        let val = $(this).val();
        if (val.length > 0) {
            typingTimer = setTimeout(function() {
                cariBarangByBarcode(val);
            }, doneTypingInterval);
        }
    });

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
        $('#origin_room').val('-');
        $('#stock_display').val('0');
    }

    // --- EVENT PILIH MANUAL ---
    $('#item_id').on('change', function() {
        let opt = $(this).find(':selected');
        let nama = opt.data('name');

        if (nama) {
            $('#nama_barang_auto').val("✅ " + nama);
            $('#nama_barang_auto').css({
                'background-color': '#4e73df',
                'color': 'white'
            });

            $('#origin_room').val(opt.data('room') || '-');
            $('#stock_display').val(opt.data('stock') + " " + (opt.data('unit') || 'Unit'));

            if (opt.data('stock') == 0) {
                alert("Perhatian: Stok barang ini 0!");
            }
        }
    });

    // --- KAMERA HP ---
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