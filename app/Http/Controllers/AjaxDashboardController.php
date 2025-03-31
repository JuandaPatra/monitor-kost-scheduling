<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Penghuni;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AjaxDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // return DataTables::of(Penghuni::query())->make(true); // ✅ Pakai DataTables::of()


        // return response()->json(Penghuni::all());
        // return DataTables::of($penghuni)
        // ->addColumn('status', function ($data) {
        //     return $data->pembayaran_terakhir && $data->pembayaran_terakhir->status == 'Sudah Bayar'
        //         ? '<span class="badge bg-success">Sudah Bayar</span>'
        //         : '<span class="badge bg-danger">Belum Bayar</span>';
        // })
        // ->editColumn('tanggal_bayar', function ($data) {
        //     return $data->pembayaran_terakhir->tanggal_bayar ?? '-';
        // })
        // ->rawColumns(['status'])
        // ->make(true);

        $bulanIni = Carbon::now()->format('Y-m-01'); // Format "YYYY-MM"

        $pembayaran = Pembayaran::where('bulan_tahun', $bulanIni)
            ->join('penghunis', 'pembayarans.penghuni_id', '=', 'penghunis.id')
            ->join('kamars', 'penghunis.kamar_id', '=', 'kamars.id')
            ->join('kosts', 'kosts.id', '=', 'kamars.kost_id')
            ->select(
                'pembayarans.id',
                DB::raw("DATE_FORMAT(pembayarans.tanggal_bayar, '%d-%m-%Y') as tanggal_bayar"),
                'pembayarans.status',
                'penghunis.nama as nama_penghuni', // Tambahkan nama penghuninya,
                'kamars.nomor_kamar as nomor_kamar',
                'kosts.nama as nama_kost'
            );

            return DataTables::of($pembayaran)
            ->filterColumn('nama_penghuni', function ($query, $keyword) {
                $query->where('penghunis.nama', 'LIKE', "%" . $keyword . "%");
            })
            ->filterColumn('nomor_kamar', function ($query, $keyword) {
                $query->where('kamars.nomor_kamar', 'LIKE', "%" . $keyword . "%");
            })
            ->filterColumn('nama_kost', function ($query, $keyword) {
                $query->whereHas('penghuni.kamar.kost', function ($q) use ($keyword) {
                    $q->where('kosts.nama', 'LIKE', "%" . $keyword . "%");
                });
            })
            ->editColumn('status', function ($row) {
                return $row->status === 'Sudah Bayar'
                    ? '<span class="badge bg-success">Lunas</span>'
                    : '<span class="badge bg-warning">Belum Lunas</span>';
            })
            ->addColumn('update_status', function ($row) {
                return '<button class="btn btn-sm btn-primary update-status" data-id="'.$row->id.'">Ubah Status</button>';
            })
            ->rawColumns(['status', 'update_status'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
