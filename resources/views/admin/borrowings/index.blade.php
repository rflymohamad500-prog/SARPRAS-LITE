@extends('layouts.admin')

@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Peminjaman Barang</h1>

    <div>
        <a href="{{ route('admin.borrowings.create') }}" class="btn btn-primary btn-sm shadow-sm mr-2">
            <i class="fas fa-plus fa-sm text-white-50"></i> Pinjam Baru
        </a>

        <button type="button" onclick="confirmDelete()" class="btn btn-danger btn-sm shadow-sm" id="btnDelete" style="display: none;">
            <i class="fas fa-trash fa-sm text-white-50"></i> Hapus Terpilih
        </button>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Riwayat Transaksi</h6>
    </div>
    <div class="card-body">

        <form action="{{ route('admin.borrowings.bulk_destroy') }}" method="POST" id="bulkDeleteForm">
            @csrf
            @method('DELETE')

            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%" class="text-center">
                                <input type="checkbox" id="checkAll" style="cursor: pointer; transform: scale(1.2);">
                            </th>
                            <th>Status</th>
                            <th>Barang</th>
                            <th>Peminjam</th>
                            <th>Lokasi Tujuan</th>
                            <th>Tgl Pinjam</th>
                            <th>Rencana Kembali</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($borrowings as $borrowing)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="ids[]" value="{{ $borrowing->id }}" class="check-item" style="cursor: pointer; transform: scale(1.2);">
                            </td>

                            <td>
                                @if($borrowing->status == 'borrowed')
                                <span class="badge badge-warning px-2 py-1">Dipinjam</span>
                                @if(now() > $borrowing->expected_return_date)
                                <div class="text-danger small font-weight-bold mt-1">Telat!</div>
                                @endif
                                @else
                                <span class="badge badge-success px-2 py-1">Sudah Kembali</span>
                                <div class="small text-muted mt-1" style="font-size: 11px;">
                                    <i class="fas fa-check"></i> {{ $borrowing->return_date ? date('d/m/Y', strtotime($borrowing->return_date)) : '-' }}
                                </div>
                                @endif
                            </td>

                            <td>
                                <strong>{{ $borrowing->item->name }}</strong>
                                <div class="small text-muted"><i class="fas fa-barcode"></i> {{ $borrowing->item->code }}</div>
                            </td>

                            <td>
                                <i class="fas fa-user fa-sm text-gray-400 mr-1"></i> {{ $borrowing->borrower_name }}
                            </td>

                            <td>
                                <span class="text-primary font-weight-bold">
                                    <i class="fas fa-map-marker-alt mr-1"></i> {{ $borrowing->item->room->name ?? '-' }}
                                </span>
                            </td>

                            <td>{{ date('d-m-Y', strtotime($borrowing->borrow_date)) }}</td>

                            <td>
                                {{ $borrowing->expected_return_date ? date('d-m-Y', strtotime($borrowing->expected_return_date)) : '-' }}
                            </td>

                            <td class="text-center">
                                @if($borrowing->status == 'borrowed')
                                <form action="{{ route('admin.borrowings.return', $borrowing->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah barang ini sudah diterima kembali dengan kondisi baik?');">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm btn-block shadow-sm" title="Klik jika barang sudah kembali">
                                        <i class="fas fa-check-circle"></i> Kembali
                                    </button>
                                </form>
                                @else
                                <button class="btn btn-secondary btn-sm btn-block" disabled style="cursor: not-allowed; opacity: 0.6;">
                                    <i class="fas fa-check-double"></i> Selesai
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 text-gray-300"></i><br>
                                Belum ada data peminjaman.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        <div class="mt-3">
            {{ $borrowings->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // 1. Fitur Check All (Pilih Semua)
    $("#checkAll").click(function() {
        // Ubah semua checkbox item sesuai status checkbox induk
        $('input:checkbox.check-item').not(this).prop('checked', this.checked);
        toggleDeleteButton();
    });

    // 2. Cek jika salah satu item diklik
    $(".check-item").change(function() {
        toggleDeleteButton();

        // Jika semua item terpilih, checklist induk juga nyala
        if ($('.check-item:checked').length == $('.check-item').length) {
            $("#checkAll").prop('checked', true);
        } else {
            $("#checkAll").prop('checked', false);
        }
    });

    // 3. Menampilkan/Menyembunyikan Tombol Hapus
    function toggleDeleteButton() {
        if ($('.check-item:checked').length > 0) {
            $('#btnDelete').fadeIn(); // Munculkan tombol merah
        } else {
            $('#btnDelete').fadeOut(); // Sembunyikan tombol merah
        }
    }

    // 4. Konfirmasi Hapus dengan SweetAlert
    function confirmDelete() {
        var total = $('.check-item:checked').length;

        Swal.fire({
            title: 'Yakin hapus ' + total + ' data?',
            text: "Data riwayat peminjaman yang dipilih akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('bulkDeleteForm').submit();
            }
        })
    }
</script>
@endpush