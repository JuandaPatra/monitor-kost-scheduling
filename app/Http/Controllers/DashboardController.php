<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Pembayaran;
use App\Models\Penghuni;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $totalPenghuni = Penghuni::where('status', '=', 'aktif')->count();

        $totalKamar = Kamar::count();

        $totalPembayaranLunasBulanIni =  DB::table('pembayarans')
        ->join('penghunis', 'pembayarans.penghuni_id', '=', 'penghunis.id')
        ->join('kamars', 'penghunis.kamar_id', '=', 'kamars.id')
        ->where('pembayarans.bulan_tahun', Carbon::now()->format('Y-m-01'))
        ->where('pembayarans.status', 'Sudah Bayar')
        ->sum('kamars.harga');

        $totalPembayaranLunasBulanIni = number_format($totalPembayaranLunasBulanIni, 0, ',', '.');
        return view('dashboard', compact('totalPenghuni', 'totalKamar', 'totalPembayaranLunasBulanIni'));
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

    public function updateStatusPembayaran(Request $request,$id)
    {

        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->status = $request->status;
        $pembayaran->save();


        return response()->json(['message' => 'Status berhasil diperbarui!']);
    }
}
