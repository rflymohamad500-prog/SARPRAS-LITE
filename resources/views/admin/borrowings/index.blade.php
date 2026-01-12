@extends('layouts.admin')

@section('title', 'Daftar Peminjaman')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Data Peminjaman Barang</h6>
        <a href="{{ route('admin.borrowings.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Pinjam Baru
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 10%">Status</th>
                        <th style="width: 20%">Barang</th>
                        <th style="width: 15%">Peminjam</th>
                        <th style="width: 15%">Lokasi Tujuan</th>
                        <th style="width: 15%">Tgl Pinjam</th>
                        <th style="width: 15%">Rencana Kembali</th>
                        <th style="width: 10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrowings as $pinjam)
                    <tr>
                        <td class="text-center">
                            @if($pinjam->status == 'borrowed')
                            <span class="badge badge-warning px-2 py-1">Sedang Dipinjam</span>
                            @else
                            <span class="badge badge-success px-2 py-1">Sudah Kembali</span>
                            <br><small class="text-muted">{{ $pinjam->actual_return_date }}</small>
                            @endif
                        </td>

                        <td>
                            <strong>{{ $pinjam->item->name }}</strong>
                            <br>
                            <small class="text-muted"><i class="fas fa-barcode"></i> {{ $pinjam->item->code }}</small>
                        </td>

                        <td>
                            <i class="fas fa-user fa-sm text-gray-400"></i> {{ $pinjam->borrower_name }}
                        </td>

                        <td>
                            <span class="text-primary font-weight-bold">
                                <i class="fas fa-map-marker-alt"></i> {{ $pinjam->location }}
                            </span>
                        </td>

                        <td>{{ date('d-m-Y', strtotime($pinjam->borrow_date)) }}</td>

                        <td>
                            {{ date('d-m-Y', strtotime($pinjam->return_date_plan)) }}

                            @if($pinjam->status == 'borrowed' && now() > $pinjam->return_date_plan)
                            <br><span class="badge badge-danger">Telat!</span>
                            @endif
                        </td>

                        <td class="text-center">
                            @if($pinjam->status == 'borrowed')
                            <form action="{{ route('admin.borrowings.return', $pinjam->id) }}" method="POST" onsubmit="return confirm('Apakah barang ini sudah diterima kembali dengan kondisi baik?');">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success btn-sm btn-block" title="Klik jika barang sudah kembali">
                                    <i class="fas fa-check-circle"></i> Kembali
                                </button>
                            </form>
                            @else
                            <button class="btn btn-secondary btn-sm btn-block" disabled>
                                <i class="fas fa-check"></i> Selesai
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <img src="https://img.icons8.com/ios/50/000000/empty-box.png" width="50" class="mb-2" /><br>
                            Belum ada data peminjaman.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection