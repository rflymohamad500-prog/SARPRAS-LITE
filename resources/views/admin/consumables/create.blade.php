@extends('layouts.admin')

@section('title', 'Input Barang Habis Pakai')

@section('content')

<div class="card shadow mb-4">
    <div class="card-header py-3 bg-primary">
        <h6 class="m-0 font-weight-bold text-white">Input Barang Habis Pakai</h6>
    </div>

    <div class="card-body">

        <div id="camera-area" class="mb-3 d-none">
            <div class="card border-success">
                <div class="card-header bg-success text-white py-1 px-3 d-flex justify-content-between align-items-center">
                    <span class="small"><i class="fas fa-camera"></i> Arahkan kamera ke barcode</span>
                    <button type="button" class="close text-white" onclick="stopCamera()">&times;</button>
                </div>
                <div class="card-body p-0 text-center">
                    <div id="reader" style="width: 100%; max-width: 300px; margin: auto;"></div>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.consumables.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">

                <div class="col-md-6">

                    <div class="form-group">
                        <label class="text-secondary font-weight-bold">Kode Barang (System)</label>
                        <input type="text" name="code" class="form-control bg-light" value="{{ $kodeOtomatis }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="text-success font-weight-bold">Barcode Kemasan (Scan)</label>
                        <div class="input-group">
                            <input type="text" id="barcode_input" name="barcode" class="form-control" placeholder="Klik tombol scan -->" autofocus>
                            <div class="input-group-append">
                                <button class="btn btn-success" type="button" onclick="startCamera()">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">Barcode pada bungkus ATK (Spidol/Kertas/Tinta).</small>
                    </div>

                    <div class="form-group">
                        <label class="text-secondary font-weight-bold">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Kertas HVS A4, Spidol Boardmarker" required>
                    </div>

                    <div class="form-group">
                        <label class="text-secondary">Foto Barang</label>

                        <div class="mb-2 d-none" id="preview-container">
                            <img id="img-preview" src="#" alt="Preview Foto" class="img-thumbnail shadow-sm" style="max-height: 200px; width: auto;">
                        </div>

                        <div class="custom-file">
                            <input type="file" name="image" class="custom-file-input" id="customFile" onchange="previewImage()" accept="image/*">
                            <label class="custom-file-label text-muted" for="customFile">Choose File</label>
                        </div>
                        <small class="text-muted">Format: JPG, PNG. Maks 2MB.</small>
                    </div>

                </div>

                <div class="col-md-6">

                    <div class="form-group">
                        <label class="text-secondary font-weight-bold">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="text-secondary font-weight-bold">Lokasi Penyimpanan <span class="text-danger">*</span></label>
                        <select name="room_id" class="form-control" required>
                            <option value="">-- Pilih Ruangan --</option>
                            @foreach($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="text-secondary">Stok Awal</label>
                            <input type="number" name="quantity" class="form-control" value="0" min="0">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="text-secondary">Satuan</label>
                            <select name="unit" class="form-control">
                                <option value="Pcs">Pcs</option>
                                <option value="Rim">Rim</option>
                                <option value="Box">Box</option>
                                <option value="Pack">Pack</option>
                                <option value="Botol">Botol</option>
                                <option value="Lembar">Lembar</option>
                                <option value="Unit">Unit</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="text-secondary">Keterangan (Opsional)</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Cth: Untuk jatah bulanan tata usaha"></textarea>
                    </div>

                </div>
            </div>

            <hr>

            <button type="submit" class="btn btn-primary shadow-sm">
                <i class="fas fa-save mr-1"></i> Simpan Data
            </button>
            <a href="{{ route('admin.consumables.index') }}" class="btn btn-secondary shadow-sm">Batal</a>

        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    // 1. FUNGSI PREVIEW GAMBAR (BARU)
    function previewImage() {
        const fileInput = document.getElementById('customFile');
        const previewContainer = document.getElementById('preview-container');
        const imgPreview = document.getElementById('img-preview');
        const label = fileInput.nextElementSibling;

        // Cek apakah ada file yang dipilih
        if (fileInput.files && fileInput.files[0]) {
            // Update nama file di label
            let fileName = fileInput.files[0].name;
            label.classList.add("selected");
            label.innerHTML = fileName;

            // Baca file gambar
            const oFReader = new FileReader();
            oFReader.readAsDataURL(fileInput.files[0]);

            oFReader.onload = function(oFREvent) {
                // Munculkan container preview dan set src gambar
                previewContainer.classList.remove('d-none');
                imgPreview.src = oFREvent.target.result;
            }
        }
    }

    // 2. Logic Scanner USB
    $('#barcode_input').on('keypress', function(e) {
        if (e.which == 13) {
            e.preventDefault();
            // Optional: alert('Barcode terdeteksi: ' + $(this).val());
        }
    });

    // 3. Logic Kamera HP
    let html5QrcodeScanner = null;

    function startCamera() {
        $('#camera-area').removeClass('d-none');
        html5QrcodeScanner = new Html5QrcodeScanner("reader", {
            fps: 10,
            qrbox: 250
        }, false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    }

    function onScanSuccess(decodedText, decodedResult) {
        stopCamera();
        $('#barcode_input').val(decodedText);
    }

    function onScanFailure(error) {}

    function stopCamera() {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear().then(_ => {
                $('#camera-area').addClass('d-none');
            }).catch(error => {
                console.error("Failed to clear html5QrcodeScanner. ", error);
            });
        } else {
            $('#camera-area').addClass('d-none');
        }
    }
</script>
@endpush