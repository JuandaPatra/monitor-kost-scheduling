<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penghuni extends Model
{

    protected $fillable = [
        'nama',
        'nomor_hp',
        'kamar_id',
        'status',
        'tanggal_masuk'
    ];
    public function pembayaran_terakhir()
    {
        return $this->hasOne(Pembayaran::class)->latest();
    }

    public function kamar()
    {
        return $this->hasOne(Kamar::class);
    }
}
