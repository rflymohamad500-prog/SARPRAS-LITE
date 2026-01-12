@extends('layouts.admin')

@section('title', 'Edit Barang')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 bg-warning">
        <h6 class="m-0 font-weight-bold text-white">
            <i class="fas fa-edit"></i> Edit Barang Tetap
        </h6>
    </div>
    <div class="card-body">

        <div class="card bg-light mb-4 border-left-warning">
            <div class="card-body p-3">
                <label class="font-weight-bold text-warning">
                    <i class="fas fa-barcode"></i> SCANNER ALAT (USB) - Ganti Barcode
                </label>
                <div class="input-group">
                    <input type="text" id="scanner_input" class="form-control font-weight-bold"
                        placeholder="Scan barcode baru disini jika ingin mengubahnya..." autocomplete="off"
                        style="font-size: 1.2rem; letter-spacing: 1px;">

                    <div class="input-group-append">
                        <button class="btn btn-secondary" type="button" onclick="$('#scanner_input').val('').focus();">
                            <i class="fas fa-sync"></i> Reset
                        </button>
                    </div>
                </div>
                <small class="text-muted">Hanya gunakan ini jika barcode fisik barang rusak/diganti.</small>
            </div>
        </div>

        <div id="camera-area" class="mb-4 d-none">
            <div class="card border-warning">
                <div class="card-header bg-warning text-white py-2">
                    <span class="font-weight-bold"><i class="fas fa-camera"></i> Scan Barcode Baru</span>
                    <button type="button" class="close text-white" onclick="stopCamera()"><span>&times;</span></button>
                </div>
                <div class="card-body text-center">
                    <div id="reader" style="width: 100%; max-width: 400px; margin: auto;"></div>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kode Barang (Sistem)</label>
                        <input type="text" name="code" class="form-control bg-light" value="{{ $item->code }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-success">Barcode Pabrik</label>
                        <div class="input-group">
                            <input type="text" id="barcode_input" name="barcode" class="form-control font-weight-bold text-primary"
                                value="{{ old('barcode', $item->barcode) }}">
                            <div class="input-group-append">
                                <button class="btn btn-warning" type="button" onclick="startCamera()">
                                    <i class="fas fa-camera"></i> Scan
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" id="name_input" name="name" class="form-control" value="{{ old('name', $item->name) }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah Stok</label>
                                <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $item->quantity) }}" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Satuan</label>
                                <select name="unit" class="form-control" required>
                                    <option value="">-- Pilih --</option>
                                    @php $units = ['Buah','Unit','Set','Pcs','Rim','Box']; @endphp
                                    @foreach($units as $u)
                                    <option value="{{ $u }}" {{ (old('unit', $item->unit) == $u) ? 'selected' : '' }}>{{ $u }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="category_id" class="form-control" required>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $item->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Lokasi</label>
                        <select name="room_id" class="form-control" required>
                            @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ $item->room_id == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Kondisi</label>
                        <select name="condition" class="form-control">
                            <option value="baik" {{ $item->condition == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak_ringan" {{ $item->condition == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="rusak_berat" {{ $item->condition == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Harga (Rp)</label>
                        <input type="number" name="price" class="form-control" value="{{ old('price', $item->price) }}">
                    </div>

                    <div class="form-group">
                        <label>Sumber Dana</label>
                        <select name="source" class="form-control">
                            <option value="">-- Pilih Sumber Dana --</option>
                            @php $sources = ['BOS', 'APBN', 'Komite', 'Yayasan', 'APBD', 'Hibah', 'Lainnya']; @endphp
                            @foreach($sources as $s)
                            <option value="{{ $s }}" {{ (old('source', $item->source) == $s) ? 'selected' : '' }}>
                                {{ $s == 'BOS' ? 'Dana BOS' : ($s == 'APBD' ? 'APBD / Pemerintah' : $s) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Ganti Foto (Opsional)</label>
                        <div class="row align-items-center">
                            <div class="col-auto">
                                @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" width="60" class="rounded border">
                                @else
                                <span class="text-muted small">No Image</span>
                                @endif
                            </div>
                            <div class="col">
                                <input type="file" name="image" class="form-control-file">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Perbarui Data</button>
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
            title: 'Barcode Baru: ' + cleanCode
        });
        $('#scanner_input').val('');
    }

    let typingTimer;
    let doneTypingInterval = 200;
    $('#scanner_input').on('input', function() {
        clearTimeout(typingTimer);
        let val = $(this).val();
        if (val.length > 0) typingTimer = setTimeout(function() {
            processScan(val);
        }, doneTypingInterval);
    });
    $('#scanner_input').on('keypress', function(e) {
        if (e.which == 13) {
            e.preventDefault();
            clearTimeout(typingTimer);
            processScan($(this).val());
        }
    });

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
        if (html5QrcodeScanner) html5QrcodeScanner.clear().then(() => {
            $('#camera-area').addClass('d-none');
        });
        else $('#camera-area').addClass('d-none');
    }
</script>
@endpush