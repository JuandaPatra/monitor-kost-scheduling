<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Kost;
use App\Models\Pembayaran;
use App\Models\Penghuni;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PenghuniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $totalPenghuni = Penghuni::where('status', '=', 'Aktif')->count();
        $totalKamar = Kamar::count();

        $totalPembayaanBulanIni = DB::table('pembayarans')
        ->join('penghunis', 'pembayarans.penghuni_id', '=', 'penghunis.id')
        ->join('kamars', 'penghunis.kamar_id', '=', 'kamars.id')
        ->where('pembayarans.bulan_tahun', Carbon::now()->format('Y-m-01'))
        ->where('pembayarans.status', 'Sudah Bayar')
        ->sum('kamars.harga');

        return view('penghuni.index', compact('totalPenghuni','totalKamar', 'totalPembayaanBulanIni'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('penghuni.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{

            $bulanTahun = Carbon::parse($request->tanggal_bayar)->format('Y-m-01'); // Ambil YYYY-MM

            $request->validate([
                'nama' => 'required',
                'nomor_hp' => 'required',
                'kamar_id' => 'required',
                'tanggal_bayar' => 'required',
                'status'        => 'required'
            ]);

            $penghuni = Penghuni::create([
                'nama' => $request->nama,
                'nomor_hp' => $request->nomor_hp,
                'kamar_id' => $request->kamar_id,
                'status'    => 'Aktif',
                'tanggal_masuk' => $request->tanggal_bayar
            ]); 



            $findPenghuni = Penghuni::where('nama', '=', $request->nama)->first();
            $pembayaran = Pembayaran::create([
                'penghuni_id' => $findPenghuni->id,
                'bulan_tahun' => $bulanTahun,
                'status' => 'Sudah Bayar',
                'tanggal_bayar' => $request->tanggal_bayar
            ]);

            $kamar = Kamar::where('id', '=', $request->kamar_id)->first();

            $kamar->update([
                'status' => 'Terisi'
            ]);

            return response()->json(['message' => 'success']);

        }catch(Exception $e){
            return $e;

        }
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

    public function ajaxListPenghuni(Request $request)
    {
        $penghuni = Penghuni::query();

        $penghuni->select('penghunis.*', 'kamars.nomor_kamar as nomor_kamar', 'kosts.nama as nama_kost')
        ->leftJoin('kamars', 'penghunis.kamar_id', '=', 'kamars.id')
        ->leftJoin('kosts', 'kamars.kost_id', '=', 'kosts.id');


        return DataTables::of($penghuni)
        ->filterColumn('nomor_kamar', function($query, $keyword) {
            $query->where('kamars.nomor_kamar', 'LIKE', "%{$keyword}%");
        })
        ->filterColumn('nama_kost', function($query, $keyword) {
            $query->where('kosts.nama', 'LIKE', "%{$keyword}%");
        })
        ->make(true);

    }
}
