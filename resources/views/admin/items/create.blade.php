@extends('layouts.admin')

@section('title', 'Tambah Barang')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 bg-primary">
        <h6 class="m-0 font-weight-bold text-white">Form Input Barang Tetap</h6>
    </div>
    <div class="card-body">

        <div class="card bg-light mb-4 border-left-primary">
            <div class="card-body p-3">
                <label class="font-weight-bold text-primary">
                    <i class="fas fa-barcode"></i> SCANNER ALAT (USB)
                </label>
                <div class="input-group">
                    <input type="text" id="scanner_input" class="form-control font-weight-bold"
                        placeholder="Klik sini, lalu scan barcode kemasan..." autofocus autocomplete="off"
                        style="font-size: 1.2rem; letter-spacing: 1px;">

                    <div class="input-group-append">
                        <button class="btn btn-secondary" type="button" onclick="$('#scanner_input').val('').focus();">
                            <i class="fas fa-sync"></i> Reset
                        </button>
                    </div>
                </div>
                <small class="text-muted">Barcode yang discan akan otomatis masuk ke kolom "Barcode Pabrik".</small>
            </div>
        </div>

        <div id="camera-area" class="mb-4 d-none">
            <div class="card border-success">
                <div class="card-header bg-success text-white py-2">
                    <span class="font-weight-bold"><i class="fas fa-camera"></i> Arahkan Kamera ke Barcode</span>
                    <button type="button" class="close text-white" onclick="stopCamera()">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="card-body text-center">
                    <div id="reader" style="width: 100%; max-width: 400px; margin: auto;"></div>
                    <small class="text-muted mt-2 d-block">Kamera akan mati otomatis setelah barcode terdeteksi.</small>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kode Barang (System)</label>
                        <input type="text" name="code" class="form-control" value="{{ $kodeOtomatis }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-success">Barcode Pabrik</label>
                        <div class="input-group">
                            <input type="text" id="barcode_input" name="barcode" class="form-control font-weight-bold text-primary"
                                value="{{ old('barcode') }}"
                                placeholder="--- Scan atau Ketik ---">

                            <div class="input-group-append">
                                <button class="btn btn-success" type="button" onclick="startCamera()">
                                    <i class="fas fa-camera"></i> Scan Kamera
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" id="name_input" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Contoh: Laptop Asus">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah Awal <span class="text-danger">*</span></label>
                                <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Satuan <span class="text-danger">*</span></label>
                                <select name="unit" class="form-control" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Buah">Buah</option>
                                    <option value="Unit">Unit</option>
                                    <option value="Set">Set</option>
                                    <option value="Pcs">Pcs</option>
                                    <option value="Rim">Rim</option>
                                    <option value="Box">Box</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Lokasi <span class="text-danger">*</span></label>
                        <select name="room_id" class="form-control" required>
                            <option value="">-- Pilih Lokasi --</option>
                            @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Kondisi</label>
                        <select name="condition" class="form-control">
                            <option value="baik">Baik</option>
                            <option value="rusak_ringan">Rusak Ringan</option>
                            <option value="rusak_berat">Rusak Berat</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Harga Per Unit (Rp)</label>
                        <input type="number" name="price" class="form-control" value="{{ old('price', 0) }}">
                    </div>

                    <div class="form-group">
                        <label>Sumber Dana <span class="text-danger">*</span></label>
                        <select name="source" class="form-control" required>
                            <option value="">-- Pilih Sumber Dana --</option>
                            <option value="BOS" {{ old('source') == 'BOS' ? 'selected' : '' }}>Dana BOS</option>
                            <option value="APBN" {{ old('source') == 'APBN' ? 'selected' : '' }}>APBN</option>
                            <option value="Komite" {{ old('source') == 'Komite' ? 'selected' : '' }}>Komite / Orang Tua</option>
                            <option value="Yayasan" {{ old('source') == 'Yayasan' ? 'selected' : '' }}>Yayasan</option>
                            <option value="APBD" {{ old('source') == 'APBD' ? 'selected' : '' }}>APBD / Pemerintah</option>
                            <option value="Hibah" {{ old('source') == 'Hibah' ? 'selected' : '' }}>Hibah / Sumbangan</option>
                            <option value="Lainnya" {{ old('source') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Pengadaan</label>
                        <input type="date" name="purchase_date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="form-group">
                        <label>Foto Barang (Opsional)</label>
                        <input type="file" name="image" class="form-control-file">
                        <small class="text-muted">Format: JPG, PNG. Maks 2MB.</small>
                    </div>
                </div>
            </div>

            <hr>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Barang</button>
            <a href="{{ route('admin.items.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#scanner_input').focus();
    });

    // --- FUNGSI PROSES HASIL SCAN ---
    function processScan(code) {
        let cleanCode = code ? code.toString().trim() : '';
        if (cleanCode.length < 3) return;

        $('#barcode_input').val(cleanCode);

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        Toast.fire({
            icon: 'success',
            title: 'Barcode Terisi: ' + cleanCode
        });

        $('#scanner_input').val('');
        $('#name_input').focus();
    }

    // --- LOGIKA SCANNER USB ---
    let typingTimer;
    let doneTypingInterval = 200;

    $('#scanner_input').on('input', function() {
        clearTimeout(typingTimer);
        let val = $(this).val();
        if (val.length > 0) {
            typingTimer = setTimeout(function() {
                processScan(val);
            }, doneTypingInterval);
        }
    });

    $('#scanner_input').on('keypress', function(e) {
        if (e.which == 13) {
            e.preventDefault();
            clearTimeout(typingTimer);
            processScan($(this).val());
        }
    });

    // --- LOGIKA KAMERA HP ---
    let html5QrcodeScanner = null;

    function startCamera() {
        $('#camera-area').removeClass('d-none');
        html5QrcodeScanner = new Html5QrcodeScanner("reader", {
            fps: 10,
            qrbox: {
                width: 250,
                height: 150
            }
        }, false);
        html5QrcodeScanner.render((decodedText) => {
            stopCamera();
            processScan(decodedText);
        }, (error) => {});
    }

    function stopCamera() {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear().then(() => {
                $('#camera-area').addClass('d-none');
            });
        } else {
            $('#camera-area').addClass('d-none');
        }
    }
</script>
@endpush