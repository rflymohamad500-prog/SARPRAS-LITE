@extends('layouts.admin')

@section('title', 'Tambah Ruangan')

@section('content')
<div class="card shadow mb-4" style="max-width: 600px;">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tambah Ruangan Baru</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.rooms.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nama Ruangan</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Lab Komputer, Kelas X-A, Gudang">
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection