<?php

namespace App\Http\Controllers;

use App\Models\Email;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EmailSenderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Email::all();

        // return $data;

        return view('email.index');
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
        $request->validate([
            'email' => 'required|email',
            'status' => 'required',
        ]);

        $deleteemail = Email::where('email', '=', $request->email);

        $deleteemail->delete();

        $email = Email::create([
            'email' => $request->email,
            'status' => $request->status
        ]);

        return response()->json(['success' => 'Data berhasil disimpan!']);

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
        $email = Email::where('id','=',$id);
        $email->update([
            'email' => $request->email,
            'status' => $request->status

        ]);

        return response()->json(['success' => 'Data berhasil disimpan!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function ajaxPenerimaKamar(Request $request)
    {

        $email = Email::all();

        return DataTables::of($email)
        ->make(true);

    }
}
