@extends('layouts.app')

@section('title', '📊 Daftar Penyewa')

@section('css')
<style>
    #penghuniTable {
        width: 100% !important;
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

        div.dataTables_wrapper div.dataTables_length,
        div.dataTables_wrapper div.dataTables_filter,
        div.dataTables_wrapper div.dataTables_info,
        div.dataTables_wrapper div.dataTables_paginate {
            text-align: end !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container">
    <h2 class="py-4">📊 Email Admin</h2>

    <div class="row">
        <!-- Card Total Penghuni -->
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total Kost</h5>
                </div>
            </div>
        </div>

        <!-- Card Total Kamar -->
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Total Kamar</h5>
                </div>
            </div>
        </div>

        <!-- Card Total Pembayaran Bulan Ini -->
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>Total Penyewa Aktif</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <a href="{{route('create-kamar')}}" class="btn btn-primary d-block" style="margin-right: 1rem;">Tambah Kamar</a>
        <a href="{{route('kost.create')}}" class="btn btn-primary">Tambah Kost</a>
    </div>
    <div class="mt-4">
        <h4>📅 List Kamar</h4>
        <table id="penghuniTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Kost</th>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const successMessage = localStorage.getItem("success_message");

        if (successMessage) {
            console.log("Native DOMContentLoaded");
            Swal.fire({
                title: successMessage,
                icon: 'success',
                confirmButtonText: 'Oke'
            })
            localStorage.removeItem("success_message");
        }
    });

    $(document).ready(function() {



        $('#penghuniTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('ajaxPenerimaKamar') }}",
            columns: [{
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                
            ],
            scrollX: true
        });
    });
</script>
@endsection