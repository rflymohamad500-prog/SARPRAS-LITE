@extends('layouts.admin')

@section('title', 'Profil Saya')

@section('content')
<div class="row">

    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Foto Profil</h6>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    @if(Auth::user()->avatar)
                    <img class="img-profile rounded-circle" src="{{ asset('storage/' . Auth::user()->avatar) }}" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #4e73df;">
                    @else
                    <img class="img-profile rounded-circle" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4e73df&color=ffffff&size=150" style="width: 150px; height: 150px; border: 3px solid #4e73df;">
                    @endif
                </div>

                <h5 class="font-weight-bold text-dark">{{ Auth::user()->name }}</h5>
                <p class="text-muted mb-1">{{ Auth::user()->email }}</p>
                <span class="badge badge-primary px-3 py-2">
                    {{ ucfirst(Auth::user()->role) }}
                </span>

                <hr>
                <div class="text-left small">
                    <i class="fas fa-calendar-alt text-gray-400 mr-2"></i> Bergabung sejak: <br>
                    <span class="text-dark ml-4 font-weight-bold">{{ Auth::user()->created_at->format('d F Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8 col-lg-7">

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Edit Informasi Akun</h6>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('patch')

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama Lengkap</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Alamat Email</label>
                        <div class="col-sm-9">
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Upload Foto</label>
                        <div class="col-sm-9">
                            <input type="file" name="avatar" class="form-control-file">
                            <small class="text-muted">Format: JPG/PNG, Maks 2MB.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger">Ganti Password</h6>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Password Saat Ini</label>
                        <div class="col-sm-8">
                            <input type="password" name="current_password" class="form-control">
                            @error('current_password', 'updatePassword')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Password Baru</label>
                        <div class="col-sm-8">
                            <input type="password" name="password" class="form-control">
                            @error('password', 'updatePassword')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Konfirmasi Password</label>
                        <div class="col-sm-8">
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-8 offset-sm-4">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-key"></i> Update Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection