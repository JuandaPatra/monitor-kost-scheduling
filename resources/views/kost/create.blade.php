@extends('layouts.app')

@section('title', '📊 Daftar Penyewa')

@section('content')
<div class="container py-4">

    <div id="#success-message"></div>
    <form id="addKost">
        @csrf
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Kost</label>
            <input type="nama" class="form-control" id="nama" required>

        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control" id="alamat" aria-label="Alamat" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection


@section('js')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {

        $('#addKost').on('submit', function(e) {
            e.preventDefault()

            let data = {
                nama: $('#nama').val(),
                alamat: $('#alamat').val()
            }


            console.log(data);

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
            });

            $.ajax({
                url: "{{ route('kost.store') }}",
                type: "POST",
                data,
                success: function(response) {
                    $("#success-message").html('<div class="alert alert-success">' + response.success + '</div>');
                    $("#addKost")[0].reset();

                    setTimeout(function(){
                        window.location.replace('{{ route('kost.index') }}')

                    },2000)
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
            scrollX: true
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