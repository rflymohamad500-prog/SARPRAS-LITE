@extends('layouts.admin')

@section('title', 'Edit Barang Habis Pakai')

@section('content')
<div class="card shadow mb-4" style="max-width: 800px;">
    <div class="card-header py-3 bg-warning">
        <h6 class="m-0 font-weight-bold text-white">Edit Barang Habis Pakai</h6>
    </div>
    <div class="card-body">

        <div id="camera-area" class="mb-4 d-none">
            <div class="card border-warning">
                <div class="card-header bg-warning text-white py-2">
                    <span class="font-weight-bold"><i class="fas fa-camera"></i> Scan Barcode Baru</span>
                    <button type="button" class="close text-white" onclick="stopCamera()">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="card-body text-center">
                    <div id="reader" style="width: 100%; max-width: 400px; margin: auto;"></div>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.consumables.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kode Barang</label>
                        <input type="text" name="code" class="form-control" value="{{ $item->code }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-success">Barcode Kemasan</label>
                        <div class="input-group">
                            <input type="text" id="barcode_input" name="barcode" class="form-control"
                                value="{{ old('barcode', $item->barcode) }}"
                                placeholder="Scan ulang jika ingin ganti -->">
                            <div class="input-group-append">
                                <button class="btn btn-warning" type="button" onclick="startCamera()">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $item->name) }}" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $item->category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Lokasi Penyimpanan <span class="text-danger">*</span></label>
                        <select name="room_id" class="form-control" required>
                            <option value="">-- Pilih Ruangan --</option>
                            @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ $item->room_id == $room->id ? 'selected' : '' }}>
                                {{ $room->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Stok Saat Ini</label>
                                <input type="number" name="quantity" class="form-control" value="{{ $item->quantity }}" min="0" required>
                                <small class="text-muted">Untuk stok opname (koreksi).</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Satuan</label>
                                <select name="unit" class="form-control" required translate="no" class="notranslate">
                                    <option value="">-- Pilih --</option>
                                    <option value="Pcs" {{ $item->unit == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                                    <option value="Pak" {{ $item->unit == 'Pak' ? 'selected' : '' }}>Pak</option>
                                    <option value="Dos" {{ $item->unit == 'Dos' ? 'selected' : '' }}>Dos</option>
                                    <option value="Buah" {{ $item->unit == 'Buah' ? 'selected' : '' }}>Buah</option>
                                    <option value="Rim" {{ $item->unit == 'Rim' ? 'selected' : '' }}>Rim</option>
                                    <option value="Lusin" {{ $item->unit == 'Lusin' ? 'selected' : '' }}>Lusin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Ganti Foto (Opsional)</label>
                @if($item->image)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $item->image) }}" width="80" class="rounded border">
                </div>
                @endif
                <input type="file" name="image" class="form-control-file">
            </div>

            <hr>
            <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Perbarui Data</button>
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
        html5QrcodeScanner = new Html5QrcodeScanner("reader", {
            fps: 10,
            qrbox: {
                width: 250,
                height: 150
            }
        }, false);
        html5QrcodeScanner.render(onScanSuccess);
    }

    function onScanSuccess(decodedText, decodedResult) {
        $('#barcode_input').val(decodedText);
        stopCamera();
        Swal.fire({
            icon: 'success',
            title: 'Barcode Terbaca',
            text: decodedText,
            timer: 1500,
            showConfirmButton: false
        });
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