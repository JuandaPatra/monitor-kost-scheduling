@extends('adminlte::page')

@section('title', 'Dashboard Admin')


{{--
    @section('content_header')
        <h1>Dashboard Admin</h1>
    @endsection
     --}}

@section('content')
<div class="row">
    <!-- Total Penghuni -->
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>totalPenghuni</h3>
                <p>Total Penghuni</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <!-- Total Kamar -->
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>totalKamar</h3>
                <p>Total Kamar</p>
            </div>
            <div class="icon">
                <i class="fas fa-bed"></i>
            </div>
        </div>
    </div>

    <!-- Total Pembayaran Bulan Ini -->
    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>Rp  number_format($totalPembayaran, 0, ',', '.') </h3>
                <p>Total Pembayaran Bulan Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-wallet"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Penghuni Belum Bayar -->
<div class="card mt-4">
    <div class="card-header">
        <h3 class="card-title">Penghuni yang Belum Membayar</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
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
