@extends('layouts.admin')

@section('title', 'Data Barang Tetap (Aset)')

@section('content')

<div class="d-md-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Barang Tetap (Aset)</h1>
    <a href="{{ route('admin.items.create') }}" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Barang
    </a>
</div>

<div class="row mb-4">

    <div class="col-md-12 mb-3">
        <div class="card shadow-sm border-left-info py-2">
            <div class="card-body py-2 d-flex align-items-center justify-content-between flex-wrap">
                <form action="{{ route('admin.items.index') }}" method="GET" class="form-inline w-100">

                    @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <label class="font-weight-bold mr-2 text-gray-700"><i class="fas fa-filter"></i> Filter Kategori:</label>

                    <select name="category_id" class="form-control form-control-sm mr-2 mb-2 mb-sm-0" onchange="this.form.submit()" style="min-width: 200px;">
                        <option value="">-- Tampilkan Semua --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>

                    @if(request('category_id') || request('search'))
                    <a href="{{ route('admin.items.index') }}" class="btn btn-sm btn-secondary mb-2 mb-sm-0">
                        <i class="fas fa-sync"></i> Reset Filter
                    </a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Fisik Barang</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalItem ?? 0 }} <small>Unit</small></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-cubes fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Nilai Aset (Rp)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalHarga ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-money-bill-wave fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-body">

        <div class="d-flex justify-content-end mb-3">
            <form action="{{ route('admin.items.index') }}" method="GET" class="form-inline">
                @if(request('category_id'))
                <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                @endif

                <div class="input-group">
                    <input type="text" name="search" class="form-control bg-light border-0 small"
                        placeholder="Cari nama / kode..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0" style="vertical-align: middle;">
                <thead class="bg-light">
                    <tr class="text-center text-dark font-weight-bold">
                        <th width="4%">No</th>
                        <th width="15%">Kode & Barcode</th>
                        <th width="20%">Nama Barang</th>
                        <th width="5%">Jml</th>
                        <th width="5%">Satuan</th>
                        <th>Harga Satuan</th>
                        <th class="bg-primary text-white">Total Harga</th>
                        <th>Sumber Dana</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Kondisi</th>
                        <th width="12%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $index => $item)
                    <tr>
                        <td class="text-center align-middle">{{ $items->firstItem() + $index }}</td>

                        <td class="align-middle">
                            <div class="font-weight-bold text-primary" style="font-size: 0.95rem;">
                                {{ $item->code }}
                            </div>
                            <div class="text-muted small mt-1">
                                <i class="fas fa-barcode"></i> {{ $item->barcode ?? '-' }}
                            </div>
                        </td>

                        <td class="align-middle">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}"
                                        alt="Foto" class="rounded shadow-sm"
                                        style="width: 45px; height: 45px; object-fit: cover;">
                                    @else
                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded"
                                        style="width: 45px; height: 45px;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <span class="font-weight-bold text-dark">{{ $item->name }}</span>
                                </div>
                            </div>
                        </td>

                        <td class="text-center align-middle font-weight-bold">{{ $item->quantity }}</td>

                        <td class="text-center align-middle text-muted">{{ $item->unit }}</td>

                        <td class="align-middle text-right text-muted">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </td>

                        <td class="align-middle text-right font-weight-bold text-primary bg-light">
                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                        </td>

                        <td class="text-center align-middle">
                            <span class="badge badge-info px-2 py-1" style="background-color: #17a2b8; color: white;">
                                {{ $item->source }}
                            </span>
                        </td>

                        <td class="align-middle text-muted small text-uppercase">
                            {{ $item->category->name ?? '-' }}
                        </td>

                        <td class="align-middle text-muted small">
                            {{ $item->room->name ?? '-' }}
                        </td>

                        <td class="text-center align-middle">
                            @if($item->condition == 'baik')
                            <span class="badge badge-success px-2 py-1">Baik</span>
                            @elseif($item->condition == 'rusak_ringan')
                            <span class="badge badge-warning px-2 py-1">Rusak R.</span>
                            @else
                            <span class="badge badge-danger px-2 py-1">Rusak B.</span>
                            @endif
                        </td>

                        <td class="text-center align-middle">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-secondary btn-sm" title="Pinjam"><i class="fas fa-shopping-cart"></i></button>
                                <a href="{{ route('admin.items.show', $item->id) }}" class="btn btn-info btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.items.edit', $item->id) }}" class="btn btn-warning btn-sm text-white" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus barang ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-center py-5 text-gray-500">
                            <img src="https://img.icons8.com/ios/50/000000/opened-folder.png" class="mb-3 opacity-50" width="40" />
                            <p class="m-0">Belum ada data barang.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $items->withQueryString()->links() }}
        </div>

    </div>
</div>

@endsection