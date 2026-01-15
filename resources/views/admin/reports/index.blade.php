@extends('layouts.admin')

@section('title', 'Pusat Laporan')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-file-alt"></i> Pusat Laporan</h1>
</div>

<div class="row">

    <div class="col-lg-6 mb-4">
        <div class="card shadow border-left-primary h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Laporan Aset Tetap</h6>
            </div>
            <div class="card-body">
                <p class="mb-3">Cetak daftar aset tetap (Barang, Tanah, Bangunan) berdasarkan kategori.</p>

                <form action="{{ route('admin.reports.asset') }}" method="GET" target="_blank">
                    <div class="form-group">
                        <label class="font-weight-bold small">Filter Kategori:</label>
                        <select name="category_id" class="form-control">
                            <option value="">-- Semua Kategori --</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-print"></i> Cetak Laporan Aset
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card shadow border-left-success h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Laporan Stok Habis Pakai</h6>
            </div>
            <div class="card-body">
                <p class="mb-4">Cetak daftar barang konsumsi (ATK, Kapur, Kertas, dll).</p>
                <br> <a href="{{ route('admin.reports.consumable') }}" target="_blank" class="btn btn-success btn-block mt-3">
                    <i class="fas fa-print"></i> Cetak Laporan Stok
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card shadow border-left-info h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">Laporan Riwayat Transaksi</h6>
            </div>
            <div class="card-body">
                <p class="mb-3">Laporan barang masuk dan keluar berdasarkan periode tanggal.</p>

                <form action="{{ route('admin.reports.transaction') }}" method="GET" target="_blank">
                    <div class="form-row">
                        <div class="col">
                            <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-01') }}" required>
                        </div>
                        <div class="col">
                            <input type="date" name="end_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-info btn-block mt-3">
                        <i class="fas fa-print"></i> Cetak Transaksi
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card shadow border-left-warning h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-warning">Laporan Peminjaman</h6>
            </div>
            <div class="card-body">
                <p class="mb-3">Rekapitulasi peminjaman barang oleh guru/karyawan.</p>

                <form action="{{ route('admin.reports.borrowing') }}" method="GET" target="_blank">
                    <div class="form-row">
                        <div class="col">
                            <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-01') }}" required>
                        </div>
                        <div class="col">
                            <input type="date" name="end_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-warning btn-block mt-3 text-white">
                        <i class="fas fa-print"></i> Cetak Peminjaman
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection