@extends('layouts.admin')

@section('title', 'Cetak Label Barcode')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 bg-dark d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-white">
            <i class="fas fa-tags"></i> Pilih Barang Untuk Cetak Label
        </h6>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.labels.print') }}" method="POST" target="_blank">
            @csrf

            <div class="mb-3">
                <button type="submit" class="btn btn-dark btn-lg">
                    <i class="fas fa-print"></i> CETAK LABEL TERPILIH
                </button>
                <span class="text-muted ml-3 small">Centang barang di bawah ini, lalu klik tombol Cetak.</span>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%" class="text-center">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Ruangan</th>
                            <th>Tgl Masuk</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="item-checkbox">
                            </td>
                            <td class="font-weight-bold">{{ $item->code }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->room->name ?? '-' }}</td>
                            <td>{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script untuk "Pilih Semua" checkbox
    document.getElementById('selectAll').addEventListener('change', function(e) {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        checkboxes.forEach(chk => chk.checked = e.target.checked);
    });
</script>
@endpush