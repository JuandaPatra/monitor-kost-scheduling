@extends('layouts.app')

@section('title', '📊 Daftar Penyewa')

@section('content')
<div class="container">
    <!-- <h2 class="mb-4">📊 Dashboard Admin</h2> -->

    <div class="row">
        <!-- Card Total Penghuni -->
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total Penghuni</h5>
                    <h3>{{ $totalPenghuni }}</h3>
                </div>
            </div>
        </div>

        <!-- Card Total Kamar -->
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Total Kamar</h5>
                    <h3>{{ $totalKamar }}</h3>
                </div>
            </div>
        </div>

        <!-- Card Total Pembayaran Bulan Ini -->
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>Total Pembayaran Bulan Ini</h5>
                    <h3>Rp {{ number_format($totalPembayaanBulanIni, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <a href="{{route('penyewa.create')}}" class="btn btn-primary">Tambah Penyewa</a>
    </div>

    <div class="mt-4">
        <h4>📅 Penghuni yang Belum Membayar Bulan Ini</h4>
        <table id="penghuniTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kamar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                {{--
                    @foreach ($penghuniBelumBayar as $penghuni)
                        <tr>
                            <td>{{ $penghuni->nama }}</td>
                <td>{{ $penghuni->kamar->nomor_kamar ?? '-' }}</td>
                <td><span class="badge bg-danger">Belum Bayar</span></td>
                </tr>
                @endforeach
                --}}
            </tbody>
        </table>
    </div>
</div>
@endsection


@section('js')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#penghuniTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('ajax-list-penyewa') }}",
            columns: [{
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: null,
                    name: 'nomor_kamar',
                    render: function(data, type, row) {
                        return row.nama_kost + " - " + row.nomor_kamar;
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: false
                },
                // { data: 'tanggal_bayar', name: 'tanggal_bayar' }
            ]
        });
    });
</script>
@endsection