@extends('layouts.admin')

@section('title', 'Edit Barang Tetap')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 bg-warning">
        <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-edit"></i> Edit Barang: {{ $item->name }}</h6>
    </div>
    <div class="card-body">

        <div class="card bg-light mb-4 border-left-warning">
            <div class="card-body p-3">
                <label class="font-weight-bold text-warning">
                    <i class="fas fa-barcode"></i> UPDATE BARCODE (USB)
                </label>
                <div class="input-group">
                    <input type="text" id="scanner_input" class="form-control font-weight-bold"
                        placeholder="Scan barcode baru di sini jika ingin mengubah..." autofocus autocomplete="off">
                    <div class="input-group-append">
                        <button class="btn btn-secondary" type="button" onclick="$('#scanner_input').val('').focus();">
                            <i class="fas fa-sync"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

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

        <form action="{{ route('admin.items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <div class="row">
                <div class="col-md-6 border-right">

                    <div class="form-group">
                        <label>Kode Barang (System)</label>
                        <input type="text" name="code" class="form-control bg-light" value="{{ $item->code }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-success">Barcode Pabrik</label>
                        <div class="input-group">
                            <input type="text" id="barcode_input" name="barcode" class="form-control font-weight-bold text-primary"
                                value="{{ old('barcode', $item->barcode) }}">
                            <div class="input-group-append">
                                <button class="btn btn-warning text-white" type="button" onclick="startCamera()">
                                    <i class="fas fa-camera"></i> Scan
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" id="name_input" name="name" class="form-control" value="{{ old('name', $item->name) }}" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Jumlah <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $item->quantity) }}" min="1" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Satuan <span class="text-danger">*</span></label>
                            <select name="unit" class="form-control" required>
                                <option value="Unit" {{ $item->unit == 'Unit' ? 'selected' : '' }}>Unit</option>
                                <option value="Pcs" {{ $item->unit == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                                <option value="Set" {{ $item->unit == 'Set' ? 'selected' : '' }}>Set</option>
                                <option value="Box" {{ $item->unit == 'Box' ? 'selected' : '' }}>Box</option>
                                <option value="Buah" {{ $item->unit == 'Buah' ? 'selected' : '' }}>Buah</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Harga Per Unit (Rp)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" name="price" class="form-control" value="{{ old('price', $item->price) }}">
                        </div>
                    </div>

                </div>

                <div class="col-md-6 pl-4">

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
                        <label>Lokasi Penempatan <span class="text-danger">*</span></label>
                        <select name="room_id" class="form-control" required>
                            <option value="">-- Pilih Lokasi --</option>
                            @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ $item->room_id == $room->id ? 'selected' : '' }}>
                                {{ $room->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Kondisi Barang</label>
                        <select name="condition" class="form-control">
                            <option value="baik" {{ $item->condition == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak_ringan" {{ $item->condition == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="rusak_berat" {{ $item->condition == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Sumber Dana <span class="text-danger">*</span></label>
                        <select name="source" class="form-control" required>
                            @php $sumber = ['BOS', 'APBN', 'Komite', 'Yayasan', 'APBD', 'Hibah', 'Lainnya']; @endphp
                            <option value="">-- Pilih --</option>
                            @foreach($sumber as $s)
                            <option value="{{ $s }}" {{ $item->source == $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Pengadaan</label>
                        <input type="date" name="purchase_date" class="form-control" value="{{ $item->purchase_date }}">
                    </div>

                    <div class="form-group">
                        <label>Foto Barang</label>
                        <div class="row">
                            <div class="col-md-3">
                                @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" class="img-thumbnail w-100">
                                @else
                                <span class="badge badge-secondary py-3 px-2 w-100">No Image</span>
                                @endif
                            </div>
                            <div class="col-md-9">
                                <div class="custom-file mt-2">
                                    <input type="file" name="image" class="custom-file-input" id="customFile">
                                    <label class="custom-file-label" for="customFile">Ganti Foto...</label>
                                </div>
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengganti foto.</small>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-warning btn-icon-split shadow-sm">
                        <span class="icon text-white-50"><i class="fas fa-save"></i></span>
                        <span class="text">Simpan Perubahan</span>
                    </button>
                    <a href="{{ route('admin.items.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    // Agar nama file foto muncul saat dipilih
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    // Scanner USB Logic (Auto Enter)
    $('#scanner_input').on('keypress', function(e) {
        if (e.which == 13) {
            e.preventDefault();
            let val = $(this).val();
            if (val.length > 2) {
                $('#barcode_input').val(val);
                alert('Barcode baru diset: ' + val);
                $(this).val(''); // Clear scanner input
            }
        }
    });

    // Kamera Logic
    let html5QrcodeScanner = null;

    function startCamera() {
        $('#camera-area').removeClass('d-none');
        html5QrcodeScanner = new Html5QrcodeScanner("reader", {
            fps: 10,
            qrbox: 250
        }, false);
        html5QrcodeScanner.render((decodedText) => {
            stopCamera();
            $('#barcode_input').val(decodedText);
        }, (error) => {});
    }

    function stopCamera() {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear();
        }
        $('#camera-area').addClass('d-none');
    }
</script>
@endpush