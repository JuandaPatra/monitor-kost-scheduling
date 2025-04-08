<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Kost;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Alert;
use Exception;
use RealRashid\SweetAlert\Facades\Alert as FacadesAlert;
use Illuminate\Support\Facades\Session;

class KostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $totalKost = Kost::count();
        $totalKamar = Kamar::count();
        $totalKamarTerisi = Kamar::where('status', '=', 'Terisi')->count();

        return view('kost.index', compact('totalKost', 'totalKamar', 'totalKamarTerisi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kost.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
        ]);

        $kost = Kost::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
        ]);

        return response()->json(['success' => 'Data berhasil disimpan!', 'penghuni' => $kost]);
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

        $data = Kamar::select(
            'kamars.*',
            'kosts.id',
            'kosts.nama as nama_kost'
        )->where('kamars.id', $id)
            ->leftJoin('kosts', 'kosts.id', '=', 'kamars.kost_id')
            ->first();

        // return $data;
        return view('kost.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $data = Kamar::whereId($id);
    
            $data->update([

                'kost_id' => $request->kost_id,
                'nomor_kamar' =>$request->nomor_kamar,
                'harga' => $request->harga
            ]);

            // return $data;

            return response()->json(['message' => 'success']);

        }catch(Exception $e){
            return $e;

        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function ajaxListKost(Request $request)
    {
        $kost = Kamar::select('kamars.*', 'kosts.nama as nama_kost')
            ->leftJoin('kosts', 'kamars.kost_id', '=', 'kosts.id');

        return DataTables::of($kost)
            ->editColumn('status', function ($row) {
                if ($row->status == 'Terisi') {
                    return '<span class="badge badge-success">Terisi</span>';
                } else {
                    return '<span class="badge badge-danger">Kosong</span>';
                }
            })
            ->rawColumns(['status']) // Tambahkan agar HTML badge dirender dengan benar
            ->filterColumn('nama_kost', function ($query, $keyword) {
                $query->whereRaw("LOWER(kosts.nama) LIKE ?", ["%" . strtolower($keyword) . "%"]);
            })
            ->filterColumn('nomor_kamar', function ($query, $keyword) {
                $query->whereRaw("LOWER(kamars.nomor_kamar) LIKE ?", ["%" . strtolower($keyword) . "%"]);
            })
            ->make(true);
    }

    public function ajaxSelectKamar(Request $request)
    {
        $kosts = Kost::select('id', 'nama')->get();
        return response()->json($kosts);
    }

    public function ajaxSelectKamarPenyewa(Request $request)
    {
        $kosts = Kamar::select('kamars.id', 'kamars.nomor_kamar', 'kosts.nama as nama_kost')->leftJoin('kosts', 'kamars.kost_id', '=', 'kosts.id')
            ->where('kamars.status', 'Kosong')->get();
        return response()->json($kosts);
    }

    public function createKamar()
    {
        return view('kost.createKamar');
    }

    public function storeKamar(Request $request)
    {

        $request->validate([
            'kost_id' => 'required',
            'nomor_kamar' => 'required',
            'harga' => 'required',
        ]);

        $kost = Kamar::create([
            'kost_id' => $request->kost_id,
            'nomor_kamar' => $request->nomor_kamar,
            'harga' => $request->harga,
        ]);


        Session::flash('message', 'Kamar Berhasil ditambah');

        return response()->json(['success' => 'Data berhasil disimpan!']);
    }
}
