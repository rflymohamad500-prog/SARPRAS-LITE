@extends('layouts.admin')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h1 class="h3 mb-0 text-gray-800">Riwayat Transaksi Barang</h1>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('admin.transactions.create_in') }}" class="btn btn-success">
            <i class="fas fa-arrow-down"></i> Barang Masuk
        </a>
        <a href="{{ route('admin.transactions.create_out') }}" class="btn btn-danger">
            <i class="fas fa-arrow-up"></i> Barang Keluar
        </a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" width="100%">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Petugas</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $trx)
                    <tr>
                        <td>{{ $trx->date }}</td>
                        <td>
                            @if($trx->type == 'masuk')
                            <span class="badge badge-success">Masuk</span>
                            @else
                            <span class="badge badge-danger">Keluar</span>
                            @endif
                        </td>
                        <td>{{ $trx->item->name ?? '-' }}</td>
                        <td class="font-weight-bold">{{ $trx->amount }}</td>
                        <td>{{ $trx->user->name ?? 'System' }}</td>
                        <td>{{ $trx->notes }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection