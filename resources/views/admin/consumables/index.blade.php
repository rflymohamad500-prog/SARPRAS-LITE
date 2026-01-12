@extends('layouts.admin')

@section('title', 'Stok Barang Habis Pakai')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Stok Barang Habis Pakai (ATK)</h6>
        <a href="{{ route('admin.consumables.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Barang
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Stok Saat Ini</th>
                        <th>Satuan</th>
                        <th>Lokasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td>{{ $item->code }}</td>
                        <td>
                            {{ $item->name }}
                            @if($item->barcode)
                            <br><small class="text-muted"><i class="fas fa-barcode"></i> {{ $item->barcode }}</small>
                            @endif
                        </td>
                        <td>{{ $item->category->name ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $item->quantity < 5 ? 'badge-danger' : 'badge-success' }}" style="font-size: 1em;">
                                {{ $item->quantity }}
                            </span>
                        </td>
                        <td>{{ $item->unit ?? '-' }}</td>

                        <td>{{ $item->room->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.consumables.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.consumables.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data barang habis pakai.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection