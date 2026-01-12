@extends('layouts.admin')

@section('title', 'Detail Barang')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-info-circle"></i> Detail Barang Tetap
                </h6>
                <a href="{{ route('admin.items.index') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center border-right">
                        <div class="mb-4">
                            <label class="font-weight-bold d-block text-gray-800">Foto Barang</label>
                            @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" class="img-fluid rounded border shadow-sm" style="max-height: 250px; object-fit: cover;">
                            @else
                            <div class="bg-light d-flex align-items-center justify-content-center rounded border mx-auto" style="height: 200px; width: 200px;">
                                <i class="fas fa-image fa-4x text-gray-300"></i>
                            </div>
                            <small class="text-muted d-block mt-2">Tidak ada foto</small>
                            @endif
                        </div>

                        <div class="mt-4">
                            <label class="font-weight-bold d-block text-gray-800">QR Code System</label>
                            <div class="d-inline-block border p-3 rounded bg-white shadow-sm">
                                {!! $qrCode !!}
                            </div>
                            <small class="text-muted d-block mt-2">Scan untuk melihat detail ini di HP</small>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <h4 class="font-weight-bold text-primary mb-4">{{ $item->name }}</h4>

                        <table class="table table-borderless table-striped">
                            <tr>
                                <th style="width: 35%">Kode Sistem</th>
                                <td><span class="badge badge-primary" style="font-size: 1rem; letter-spacing: 1px;">{{ $item->code }}</span></td>
                            </tr>
                            <tr>
                                <th>Barcode Pabrik</th>
                                <td>
                                    @if($item->barcode)
                                    <span class="font-weight-bold font-monospace text-dark"><i class="fas fa-barcode"></i> {{ $item->barcode }}</span>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="bg-success text-white rounded">
                                <th>Jumlah Stok</th>
                                <td class="font-weight-bold" style="font-size: 1.1rem;">
                                    {{ $item->quantity }} {{ $item->unit }}
                                </td>
                            </tr>
                            <tr>
                                <th>Harga Per Unit</th>
                                <td class="font-weight-bold text-success" style="font-size: 1.1rem;">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </td>
                            </tr>
                            <th>Sumber Dana</th>
                            <td><span class="badge badge-info">{{ $item->source ?? '-' }}</span></td>
                            <tr>
                                <th>Kategori</th>
                                <td>{{ $item->category->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Ruangan / Lokasi</th>
                                <td><i class="fas fa-map-marker-alt text-danger"></i> {{ $item->room->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Kondisi</th>
                                <td>
                                    @if($item->condition == 'baik')
                                    <span class="badge badge-success px-3 py-2">Baik</span>
                                    @elseif($item->condition == 'rusak_ringan')
                                    <span class="badge badge-warning px-3 py-2">Rusak Ringan</span>
                                    @elseif($item->condition == 'rusak_berat')
                                    <span class="badge badge-danger px-3 py-2">Rusak Berat</span>
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Tanggal Pengadaan</th>
                                <td>
                                    {{ $item->purchase_date ? date('d F Y', strtotime($item->purchase_date)) : '-' }}
                                </td>
                            </tr>
                        </table>

                        <hr>
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.items.edit', $item->id) }}" class="btn btn-warning btn-icon-split mr-2">
                                <span class="icon text-white-50"><i class="fas fa-edit"></i></span>
                                <span class="text">Edit Data</span>
                            </a>
                            <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini secara permanen?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-icon-split">
                                    <span class="icon text-white-50"><i class="fas fa-trash"></i></span>
                                    <span class="text">Hapus</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection