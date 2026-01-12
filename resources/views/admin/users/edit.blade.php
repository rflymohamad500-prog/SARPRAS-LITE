@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="card shadow mb-4" style="max-width: 600px;">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Data User</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-group">
                <label>Role (Hak Akses)</label>
                <select name="role" class="form-control" required>
                    <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                </select>
            </div>

            <hr>
            <div class="alert alert-info small">
                <i class="fas fa-info-circle"></i> Biarkan password kosong jika tidak ingin mengubahnya.
            </div>

            <div class="form-group">
                <label>Password Baru (Opsional)</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection