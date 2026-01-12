@extends('layouts.admin')

@section('title', 'Pusat Laporan')

@section('content')
<div class="row">

    <div class="col-md-3 mb-4">
        <div class="card shadow h-100 py-2 border-left-primary">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Inventaris</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">Aset Tetap</div>
                <p class="text-muted small mt-2">Daftar aset, kondisi, dan lokasi.</p>
                <a href="{{ route('admin.reports.asset') }}" target="_blank" class="btn btn-primary btn-sm btn-block mt-3">
                    <i class="fas fa-print"></i> Cetak Semua
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card shadow h-100 py-2 border-left-success">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Stok Barang</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">Habis Pakai</div>
                <p class="text-muted small mt-2">Sisa stok ATK & operasional.</p>
                <a href="{{ route('admin.reports.consumable') }}" target="_blank" class="btn btn-success btn-sm btn-block mt-3">
                    <i class="fas fa-print"></i> Cetak Semua
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card shadow h-100 py-2 border-left-warning">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Riwayat</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">Keluar Masuk</div>

                <form action="{{ route('admin.reports.transaction') }}" method="GET" target="_blank" class="mt-3">
                    <div class="form-group mb-1"><label class="small">Dari:</label><input type="date" name="start_date" class="form-control form-control-sm" required></div>
                    <div class="form-group mb-2"><label class="small">Sampai:</label><input type="date" name="end_date" class="form-control form-control-sm" required></div>
                    <button type="submit" class="btn btn-warning btn-sm btn-block"><i class="fas fa-print"></i> Cetak</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card shadow h-100 py-2 border-left-info">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Aktivitas</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">Peminjaman</div>

                <form action="{{ route('admin.reports.borrowing') }}" method="GET" target="_blank" class="mt-3">
                    <div class="form-group mb-1"><label class="small">Dari:</label><input type="date" name="start_date" class="form-control form-control-sm" required></div>
                    <div class="form-group mb-2"><label class="small">Sampai:</label><input type="date" name="end_date" class="form-control form-control-sm" required></div>
                    <button type="submit" class="btn btn-info btn-sm btn-block"><i class="fas fa-print"></i> Cetak</button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection