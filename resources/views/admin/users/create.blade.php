@extends('layouts.admin')

@section('title', 'Tambah User')

@section('content')
<div class="card shadow mb-4" style="max-width: 600px;">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Buat Akun Baru</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label>Role (Hak Akses)</label>
                <select name="role" class="form-control" required>
                    <option value="petugas">Petugas (Hanya Transaksi)</option>
                    <option value="admin">Administrator (Full Akses)</option>
                </select>
            </div>

            <hr>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan User</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection