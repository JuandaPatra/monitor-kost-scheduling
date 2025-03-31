@extends('layouts.app')

@section('title', '📊 Daftar Penyewa')

@section('css')
<style>
    .select2-container .select2-selection--single {
        height: calc(2.25rem + 2px) !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        top: 50% !important;
        transform: translateY(-50%) !important;
    }

    .select2 .select2-container .select2-container--default .select2-container--below {
        width: 100% !important;
    }
</style>
@endsection

@section('content')
<div class="container py-4">

    <div id="#success-message"></div>
    <form id="FormAddPenyewa">
        @csrf
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Penyewa</label>
            <input type="text" class="form-control" id="nama" required>

        </div>
        <div class="mb-3">
            <label for="No Handphone" class="form-label">No Handphone</label>
            <input type="text" class="form-control" id="nohandphone" required>
        </div>

        <div class="mb-3">
            <label for="kost" class="form-label">Pilih Kost</label>
            <select id="select-kamar" name="kamar_id" class="form-control">
                <option value="">Pilih Kost</option>
            </select>

        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select id="select-status" class="form-control" aria-label="Default select example">
                <option selected>Pilih Status</option>
                <option value="Terisi">Terisi</option>
                <option value="Kosong">Kosong</option>
            </select>
        </div>

        <div class="form-group">
            <label for="tanggal_bayar">Tanggal Bayar</label>
            <div class="input-group date" id="datepicker">
                <input type="text" class="form-control" name="tanggal_bayar" id="tanggal_bayar" placeholder="Pilih tanggal">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection


@section('js')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script>
    $(document).ready(function() {

        $('#datepicker').datepicker({
            format: 'yyyy-mm-dd', // Format tanggal
            autoclose: true,
            todayHighlight: true
        });

        $('#select-kamar').select2({
            ajax: {
                url: "{{ route('ajaxSelectKamarPenyewa') }}",
                dataType: 'json',
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: `${item.nama_kost} - ${item.nomor_kamar}`

                            };
                        })
                    };
                }
            }
        })



        $("#select-status").on('change', function() {
            let selected = $(this).val()
        })

        $("#select-kamar").on('change', function() {
            let selected = $(this).val()
        })

        $('#FormAddPenyewa').on('submit', function(e) {
            e.preventDefault()

            let data = {
                nama: $('#nama').val(),
                nomor_hp: $('#nohandphone').val(),
                kamar_id: $('#select-kamar').val(),
                status : $('#select-status').val(),
                tanggal_bayar : $('#tanggal_bayar').val()
            }


            console.log(data);

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
            });

            $.ajax({
                url: "{{ route('penyewa.store') }}",
                type: "POST",
                data,
                success: function(response) {
                    $("#success-message").html('<div class="alert alert-success">' + response.success + '</div>');
                    $("#FormAddPenyewa")[0].reset();

                    setTimeout(function() {


                    }, 2000)
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessages = "";
                    $.each(errors, function(key, value) {
                        errorMessages += "<p class='text-danger'>" + value[0] + "</p>";
                    });
                    $("#success-message").html(errorMessages);
                }
            });
        })
        $('#penghuniTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('ajax-list-kost') }}",
            // columns: [
            //     { data: 'nama', name: 'nama' },
            //     { data: 'kamar.nomor_kamar', name: 'kamar.nomor_kamar' },
            //     { data: 'status', name: 'status', orderable: false, searchable: false },
            //     { data: 'tanggal_bayar', name: 'tanggal_bayar' }
            // ]
        });
    });
</script>
@endsection