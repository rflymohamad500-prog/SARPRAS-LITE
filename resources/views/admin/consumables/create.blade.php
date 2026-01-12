@extends('layouts.admin')

@section('title', 'Tambah ATK / Habis Pakai')

@section('content')
<div class="card shadow mb-4" style="max-width: 800px;">
    <div class="card-header py-3 bg-primary">
        <h6 class="m-0 font-weight-bold text-white">Input Barang Habis Pakai</h6>
    </div>
    <div class="card-body">

        <div id="camera-area" class="mb-4 d-none">
            <div class="card border-success">
                <div class="card-header bg-success text-white py-2">
                    <span class="font-weight-bold"><i class="fas fa-camera"></i> Scan Barcode Kemasan</span>
                    <button type="button" class="close text-white" onclick="stopCamera()">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="card-body text-center">
                    <div id="reader" style="width: 100%; max-width: 400px; margin: auto;"></div>
                    <small class="text-muted mt-2 d-block">Kamera akan mati otomatis setelah scan berhasil.</small>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.consumables.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kode Barang</label>
                        <input type="text" name="code" class="form-control" value="{{ $kodeOtomatis }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-success">Barcode Kemasan (Scan)</label>
                        <div class="input-group">
                            <input type="text" id="barcode_input" name="barcode" class="form-control"
                                placeholder="Klik tombol scan -->">
                            <div class="input-group-append">
                                <button class="btn btn-success" type="button" onclick="startCamera()">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">Barcode pada bungkus ATK (Spidol/Kertas).</small>
                    </div>

                    <div class="form-group">
                        <label>Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Kertas HVS A4" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Lokasi Penyimpanan <span class="text-danger">*</span></label>
                        <select name="room_id" class="form-control" required>
                            <option value="">-- Pilih Ruangan --</option>
                            @foreach($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Stok Awal</label>
                                <input type="number" name="quantity" class="form-control" value="0" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Satuan</label>
                                <select name="unit" class="form-control" required>
                                    <option value="">-- Pilih Satuan --</option>
                                    <option value="Pcs">Pcs</option>
                                    <option value="Pak">Pak</option>
                                    <option value="Dos">Dos</option>
                                    <option value="Buah">Buah</option>
                                    <option value="Rim">Rim</option>
                                    <option value="Lusin">Lusin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Foto Barang</label>
                <input type="file" name="image" class="form-control-file">
            </div>

            <hr>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Data</button>
            <a href="{{ route('admin.consumables.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    let html5QrcodeScanner = null;

    function startCamera() {
        $('#camera-area').removeClass('d-none');
        html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 150
                }
            }, false
        );
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    }

    function onScanSuccess(decodedText, decodedResult) {
        $('#barcode_input').val(decodedText);

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        Toast.fire({
            icon: 'success',
            title: 'Barcode: ' + decodedText
        });

        stopCamera();
    }

    function onScanFailure(error) {}

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