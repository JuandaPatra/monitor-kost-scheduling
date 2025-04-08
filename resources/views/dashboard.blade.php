@extends('layouts.app')

@section('title', '📊 Dashboard Admin')

@section('css')
<style>
    #penghuniTable {
        width: 100% !important;
    }

    div.dataTables_wrapper div.dataTables_length select {
        width: 50px !important;
    }

    @media screen and (max-width: 767px) {

        div.dataTables_wrapper div.dataTables_length,
        div.dataTables_wrapper div.dataTables_filter,
        div.dataTables_wrapper div.dataTables_info,
        div.dataTables_wrapper div.dataTables_paginate {
            text-align: end !important;
        }
    }

    @media screen and (max-width: 767px) {
        .table-container{
            overflow-x: scroll;
        }
    }
</style>
@endsection

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
                    <h3>Rp {{$totalPembayaranLunasBulanIni}}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <h4>📅 Rekap Status Pembayaran Bulan Ini</h4>
        <table id="penghuniTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kamar</th>
                    <th>Tenggat Waktu</th>
                    <th>Status</th>
                    <th>Update</th>
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

<!-- Modal Ubah Status -->
<div class="modal fade" id="modalUbahStatus" tabindex="-1" aria-labelledby="modalUbahStatusLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUbahStatusLabel">Ubah Status Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="pembayaran_id">
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status">
                        <option value="Sudah Bayar">Sudah Bayar</option>
                        <option value="Belum Bayar">Belum Bayar</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSimpanStatus">Simpan</button>
            </div>
        </div>
    </div>
</div>

@endsection


@section('js')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {

        $(document).on('click', '.update-status', function() {
            let id = $(this).data('id');
            let status = $(this).data('status');

            $('#pembayaran_id').val(id);
            $('#status').val(status);
            $('#modalUbahStatus').modal('show');
        });


        $('#btnSimpanStatus').on('click', function() {
            let id = $('#pembayaran_id').val();
            let status = $('#status').val();

            let data = {
                id,
                status
            }

            console.log(data)

            $.ajax({
                url: "/dashboard/update-status/" + id,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    status: status
                },
                success: function(response) {
                    alert(response.message);
                    $('#modalUbahStatus').modal('hide');
                    $('#penghuniTable').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    alert("Terjadi kesalahan!");
                }
            });
        });

        $('#penghuniTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.penghuni.data') }}",
            columns: [{
                    data: 'nama_penghuni',
                    name: 'nama_penghuni'
                },
                {
                    data: null,
                    name: 'nomor_kamar',
                    render: function(data, type, row) {
                        return row.nama_kost + " - " + row.nomor_kamar;
                    }
                },
                {
                    data: 'tanggal_bayar',
                    name: 'tanggal_bayar'
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'update_status',
                    name: 'update_status',
                    orderable: false,
                    searchable: false
                }
            ],
            scrollX: true
        });
    });
</script>
@endsection