@extends('layouts.admin')

@section('title', 'Data Barang Tetap')

@section('content')
<div class="card shadow mb-4">

    <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Data Barang Tetap (Aset)</h6>

        <form action="{{ route('admin.items.index') }}" method="GET" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
                <input type="text" name="search" class="form-control bg-light border small"
                    placeholder="Cari nama / kode..." aria-label="Search"
                    value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                    @if(request('search'))
                    <a href="{{ route('admin.items.index') }}" class="btn btn-secondary" title="Reset">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </div>
        </form>

        <a href="{{ route('admin.items.create') }}" class="btn btn-primary btn-sm shadow-sm mt-3 mt-sm-0">
            <i class="fas fa-plus"></i> Tambah Barang
        </a>
    </div>

    <div class="card-body">

        @if(request('search'))
        <div class="alert alert-info py-2 mb-3">
            Menampilkan hasil pencarian untuk: <strong>"{{ request('search') }}"</strong>
            ({{ $items->count() }} data ditemukan)
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-light text-dark font-weight-bold">
                    <tr>
                        <th style="width: 5%" class="text-center align-middle">No</th>
                        <th style="width: 15%" class="align-middle">Kode & Barcode</th>
                        <th class="align-middle">Nama Barang</th>
                        <th style="width: 5%" class="text-center align-middle">Jml</th>
                        <th style="width: 8%" class="text-center align-middle">Satuan</th>
                        <th style="width: 12%" class="text-right align-middle">Harga</th>
                        <th style="width: 10%" class="text-center align-middle">Sumber Dana</th>
                        <th style="width: 12%" class="align-middle">Kategori</th>
                        <th style="width: 10%" class="align-middle">Lokasi</th>
                        <th style="width: 8%" class="text-center align-middle">Kondisi</th>
                        <th style="width: 15%" class="text-center align-middle">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $index => $item)
                    <tr>
                        <td class="text-center align-middle">{{ $index + 1 }}</td>

                        <td class="align-middle">
                            <span class="font-weight-bold text-primary">{{ $item->code }}</span>
                            @if($item->barcode)
                            <br><small class="text-muted d-block mt-1" style="font-size: 0.85rem;">
                                <i class="fas fa-barcode"></i> {{ $item->barcode }}
                            </small>
                            @endif
                        </td>

                        <td class="align-middle">
                            <div class="d-flex align-items-center">
                                @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" width="40" height="40" class="rounded mr-2 border shadow-sm" style="object-fit: cover">
                                @else
                                <div class="rounded mr-2 bg-light border d-flex align-items-center justify-content-center text-secondary" style="width:40px; height:40px;">
                                    <i class="fas fa-image"></i>
                                </div>
                                @endif
                                <span class="font-weight-bold text-dark">{{ $item->name }}</span>
                            </div>
                        </td>

                        <td class="text-center align-middle font-weight-bold">{{ $item->quantity }}</td>
                        <td class="text-center align-middle">{{ $item->unit ?? '-' }}</td>

                        <td class="text-right align-middle text-nowrap">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </td>

                        <td class="align-middle text-center">
                            @if($item->source)
                            <span class="badge badge-info px-2 py-1">{{ $item->source }}</span>
                            @else
                            <span class="text-muted small">-</span>
                            @endif
                        </td>

                        <td class="align-middle small">{{ $item->category->name ?? '-' }}</td>
                        <td class="align-middle small">{{ $item->room->name ?? '-' }}</td>

                        <td class="text-center align-middle">
                            @if($item->condition == 'baik')
                            <span class="badge badge-success">Baik</span>
                            @elseif($item->condition == 'rusak_ringan')
                            <span class="badge badge-warning">R. Ringan</span>
                            @elseif($item->condition == 'rusak_berat')
                            <span class="badge badge-danger">R. Berat</span>
                            @else
                            <span class="badge badge-secondary">{{ $item->condition }}</span>
                            @endif
                        </td>

                        <td class="text-center align-middle">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-secondary btn-sm btn-mutate"
                                    data-id="{{ $item->id }}"
                                    data-name="{{ $item->name }}"
                                    data-current="{{ $item->room_id }}"
                                    title="Pindah Lokasi">
                                    <i class="fas fa-dolly"></i>
                                </button>

                                <a href="{{ route('admin.items.show', $item->id) }}" class="btn btn-info btn-sm" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.items.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center py-5 text-muted">
                            <i class="fas fa-search fa-3x mb-3 text-gray-300"></i><br>
                            Data tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="mutateModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="mutateForm" action="" method="POST">
                @csrf @method('PUT')
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title"><i class="fas fa-dolly"></i> Mutasi Barang (Pindah Ruang)</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Barang: <strong id="modalItemName" class="text-primary" style="font-size: 1.2em;"></strong></p>
                    <p class="text-muted small">Pilih Lokasi tujuan perpindahan barang ini.</p>

                    <div class="form-group">
                        <label class="font-weight-bold">Pindah Ke Lokasi:</label>
                        <select name="room_id" id="modalRoomSelect" class="form-control shadow-sm" required>
                            @foreach($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-secondary shadow-sm">
                        <i class="fas fa-save"></i> Simpan & Cetak Surat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script agar tombol Troli berfungsi saat diklik
    $('.btn-mutate').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var currentRoom = $(this).data('current');

        // Isi data ke dalam modal
        $('#modalItemName').text(name);
        $('#modalRoomSelect').val(currentRoom); // Pilih Lokasi yang sekarang

        // Update URL action form
        var url = "{{ route('admin.items.index') }}/" + id + "/mutate";
        $('#mutateForm').attr('action', url);

        // Tampilkan modal
        $('#mutateModal').modal('show');
    });
</script>
@endpush