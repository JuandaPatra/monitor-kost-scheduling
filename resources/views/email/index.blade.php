@extends('layouts.app')

@section('title', '📊 Daftar Penyewa')

@section('css')
<style>
    #penghuniTable {
        width: 100% !important;
    }

    div.dataTables_wrapper div.dataTables_length select {
        width: 3rem !important;
    }

    .custom-select-sm {
        padding-top: 0.25rem !important;
        padding-bottom: 0.25rem !important;
        padding-left: 0.5rem !important;
        font-size: 75% !important;
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



    <div class="d-flex justify-content-end">
        <a class="btn btn-primary btn-tambah-penerima">Tambah Penerima</a>
    </div>
    <div class="mt-4">
        <h4>📅 List Kamar</h4>
        <table id="penghuniTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>

<!-- Modal Ubah Status -->
<div class="modal fade" id="modaltambahpenerima" tabindex="-1" aria-labelledby="modaltambahpenerima" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahPenerima">Ubah Status Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="status" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" placeholder="Masukkan Email">
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status">
                        <option value="">Pilih Status</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" data-button="post" id="method">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSimpanEmailPenerima">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection


@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
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


        let id
        let method
        $('.btn-tambah-penerima').on('click', function() {
            $('#modaltambahpenerima').modal('show')

            method="post"

            $('#email').val('')
            $('#status').val('')
            $('#method').attr('button', 'post')
        })


        $('#btnSimpanEmailPenerima').on('click', function() {


            let data = {
                _token: "{{ csrf_token() }}",
                email: $('#email').val(),
                status: $('#status').val()
            }

            

            $.ajax({
                url: method === 'post' ? "/email" : `/email/${id}`,
                type: method === 'post'? "POST" : "PUT",
                data: data,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: method==="post" ? 'Berhasil menambahkan email' : 'Berhasil mengupdate email',
                        timer: 3000,
                        showConfirmButton: false
                    });
                    $('#modaltambahpenerima').modal('hide');
                    $('#penghuniTable').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    alert("Terjadi kesalahan!");
                }
            });
        })


        $('#penghuniTable tbody').on('click', 'tr td a', function(e) {
            let data = $(this).data("update")
            let status = $(this).data("status")

            id=$(this).data("id")
            $('#method').attr('button', 'put')
            method="put"
            $('#email').val(data)
            $('#status').val(status)
            $('#modaltambahpenerima').modal('show')
        })

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
            columnDefs: [{
                targets: 1, // kolom status
                render: function(data, type, row, meta) {
                    if (data == 'Aktif') {
                        return '<span class="badge badge-success">Aktif</span>';
                    } else {
                        return '<span class="badge badge-danger">Nonaktif</span>';
                    }
                }
            }, {
                targets: 0, // kolom pertama
                render: function(data, type, row, meta) {
                    // data = row.email
                    // row.status = nilai dari kolom ke-2
                    return `<a href="#" data-id="${row.id}" data-update="${data}" data-status=${row.status} >${data}</a>`;
                }
            }],
            scrollX: true
        });
    });
</script>
@endsection