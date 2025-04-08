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
    <form id="FormAddKamar">
        @csrf

        <div class="mb-3">
            <label for="kost" class="form-label">Pilih Kost</label>
            <select id="select-kamar" name="kostid" class="form-control">
                <option value="">Pilih Kost</option>
            </select>

        </div>

        <div class="mb-3">
            <label for="nomerkamar" class="form-label">Nomer Kamar</label>
            <input type="string" class="form-control" id="nomerkamar" required>

        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="harga" class="form-control" id="harga" required>
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

<script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0"></script>
<script>
    $(document).ready(function() {

        $('#select-kamar').select2({
            ajax: {
                url: "{{ route('ajaxSelectKamar') }}",
                dataType: 'json',
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.nama
                            };
                        })
                    };
                }
            }
        })

        $("#select-kamar").on('change', function(){
            let selected = $(this).val()
        })

        let cleaveHarga = new Cleave('#harga', {
            numeral: true,
            // numeralThousandsGroupStyle: 'thousand',
            // delimiter: '.', // Pakai titik sebagai pemisah ribuan
            prefix: 'Rp ', // Tambahkan "Rp" di depan angka
            rawValueTrimPrefix: true,
            numeralIntegerScale: 15,
        });

        $('#FormAddKamar').on('submit', function(e) {
            e.preventDefault()

            let rawHarga = cleaveHarga.getRawValue()
            let data = {
                kost_id: $('#select-kamar').val(),
                nomor_kamar: $('#nomerkamar').val(),
                harga: rawHarga
            }


            console.log(data);

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
            });

            $.ajax({
                url: "{{ route('store-kamar') }}",
                type: "POST",
                data,
                success: function(response) {
                    $("#success-message").html('<div class="alert alert-success">' + response.success + '</div>');
                    $("#FormAddKamar")[0].reset();


                    setTimeout(function(){
                        window.location.href = "/"
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