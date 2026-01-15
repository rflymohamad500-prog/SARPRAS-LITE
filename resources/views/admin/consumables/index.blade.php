@extends('layouts.admin')

@section('title', 'Stok Barang Habis Pakai')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Stok Barang Habis Pakai (ATK)</h1>
    <a href="{{ route('admin.consumables.create') }}" class="btn btn-success shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Barang
    </a>
</div>

<div class="card shadow mb-4 border-left-success">
    <div class="card-body">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0" style="vertical-align: middle;">
                <thead class="bg-light">
                    <tr class="text-center text-dark font-weight-bold">
                        <th width="5%">No</th>
                        <th width="10%">Foto</th>
                        <th>Kode & Nama Barang</th>
                        <th>Kategori</th>
                        <th>Stok Saat Ini</th>
                        <th>Satuan</th>
                        <th>Lokasi</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($consumables as $index => $item)
                    <tr>
                        <td class="text-center align-middle">{{ $index + 1 }}</td>

                        <td class="text-center align-middle">
                            @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}"
                                alt="Foto" class="rounded border shadow-sm"
                                style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                            <div class="bg-secondary rounded text-white d-flex align-items-center justify-content-center mx-auto"
                                style="width: 50px; height: 50px; font-size: 10px;">
                                No Pic
                            </div>
                            @endif
                        </td>

                        <td class="align-middle">
                            <div class="font-weight-bold text-dark">{{ $item->name }}</div>
                            <small class="text-muted">{{ $item->code }}</small>
                            @if($item->barcode)
                            <div class="text-muted small"><i class="fas fa-barcode"></i> {{ $item->barcode }}</div>
                            @endif
                        </td>

                        <td class="align-middle">{{ $item->category->name ?? '-' }}</td>

                        <td class="text-center align-middle">
                            @if($item->quantity == 0)
                            <span class="badge badge-danger px-2 py-1">HABIS (0)</span>
                            @elseif($item->quantity < 5)
                                <span class="badge badge-warning px-2 py-1">{{ $item->quantity }} (Menipis)</span>
                                @else
                                <span class="badge badge-success px-3 py-2" style="font-size: 1rem;">{{ $item->quantity }}</span>
                                @endif
                        </td>

                        <td class="text-center align-middle">{{ $item->unit }}</td>

                        <td class="align-middle">{{ $item->room->name ?? '-' }}</td>

                        <td class="text-center align-middle">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.consumables.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.consumables.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus barang ini?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-gray-500">
                            Belum ada data barang habis pakai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection