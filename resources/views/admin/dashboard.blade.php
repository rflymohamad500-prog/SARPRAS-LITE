@extends('layouts.admin')

@section('title', 'Dashboard Inventaris')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard & Rekap Data</h1>
    <a href="{{ route('admin.items.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-boxes fa-sm text-white-50"></i> Lihat Semua Barang
    </a>
</div>

<div class="row">

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Aset</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAset ?? 0 }} Item</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-laptop fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Stok ATK</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalATK ?? 0 }} Jenis</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-pencil-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Sedang Dipinjam</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $sedangDipinjam ?? 0 }} Transaksi</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-hand-holding fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Stok Kritis</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stokKritis ?? 0 }} Item</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-lg-7 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-white">Aktivitas Peminjaman Terakhir</h6>

                @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.borrowings.index') }}" class="text-white small"><u>Lihat Semua</u></a>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                        <thead class="bg-light">
                            <tr>
                                <th>Barang</th>
                                <th>Peminjam</th>
                                <th>Tanggal</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBorrowings ?? [] as $pinjam)
                            <tr>
                                <td>{{ $pinjam->item->name }}</td>
                                <td>{{ $pinjam->borrower_name }}</td>
                                <td>{{ date('d/m/y', strtotime($pinjam->borrow_date)) }}</td>
                                <td class="text-center">
                                    @if($pinjam->status == 'borrowed')
                                    <span class="badge badge-warning">Dipinjam</span>
                                    @else
                                    <span class="badge badge-success">Kembali</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-3">Belum ada aktivitas peminjaman.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-danger">
                <h6 class="m-0 font-weight-bold text-white">Peringatan Stok Menipis (< 5)</h6>
            </div>
            <div class="card-body">
                @if(isset($lowStockItems) && $lowStockItems->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <p class="font-weight-bold text-gray-600">Stok Aman! Tidak ada yang kritis.</p>
                </div>
                @else
                <ul class="list-group">
                    @foreach($lowStockItems ?? [] as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="font-weight-bold text-dark">{{ $item->name }}</span>
                            <div class="small text-muted">{{ $item->code }}</div>
                        </div>
                        <span class="badge badge-danger badge-pill" style="font-size: 1rem;">Sisa: {{ $item->quantity }}</span>
                    </li>
                    @endforeach
                </ul>
                <div class="alert alert-warning mt-3 text-center small mb-0">
                    <i class="fas fa-info-circle"></i> Segera lakukan pengadaan stok.
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Akses Cepat</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.transactions.create_in') }}" class="btn btn-success btn-icon-split mr-2 mb-2">
                    <span class="icon text-white-50"><i class="fas fa-plus-circle"></i></span>
                    <span class="text">Barang Masuk</span>
                </a>
                <a href="{{ route('admin.transactions.create_out') }}" class="btn btn-danger btn-icon-split mr-2 mb-2">
                    <span class="icon text-white-50"><i class="fas fa-minus-circle"></i></span>
                    <span class="text">Barang Keluar</span>
                </a>

                @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.borrowings.create') }}" class="btn btn-primary btn-icon-split mr-2 mb-2">
                    <span class="icon text-white-50"><i class="fas fa-hand-holding"></i></span>
                    <span class="text">Form Peminjaman</span>
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection