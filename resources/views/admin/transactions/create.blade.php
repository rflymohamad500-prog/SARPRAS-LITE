@extends('layouts.admin')

@section('title', 'Tambah Barang Tetap')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Formulir Masukan Barang Tetap</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kode Barang (Otomatis)</label>
                        <input type="text" class="form-control" value="INV-{{ date('Y') }}{{ rand(1000,9999) }}" readonly>
                        <small class="text-muted">Kode sistem digenerate otomatis.</small>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-success">Barcode Pabrik (Scan di sini)</label>
                        <div class="input-group">
                            <input type="text" name="barcode" class="form-control"
                                value="{{ old('barcode') }}"
                                placeholder="Contoh: 8997788 (Scan barcode kemasan)">
                            <div class="input-group-append">
                                <span class="input-group-text bg-success text-white"><i class="fas fa-barcode"></i></span>
                            </div>
                        </div>
                        <small class="text-muted">Isi jika barang memiliki barcode bawaan.</small>
                    </div>
                    <div class="form-group">
                        <label>Nama Barang *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Contoh: Laptop Asus ROG" required>
                    </div>

                    <div class="form-group">
                        <label>Kategori *</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Ruangan *</label>
                        <select name="room_id" class="form-control" required>
                            <option value="">-- Pilih Ruangan --</option>
                            @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
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
                        <label>Tanggal Pengadaan</label>
                        <input type="date" name="purchase_date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="form-group">
                        <label>Foto Barang (Opsional)</label>
                        <input type="file" name="image" class="form-control-file">
                        <small class="text-muted">Format: JPG, PNG. Maks. 2MB.</small>
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