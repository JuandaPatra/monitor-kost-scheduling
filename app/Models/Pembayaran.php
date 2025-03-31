<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $fillable = [
        'penghuni_id',
        'bulan_tahun',
        'status',
        'tanggal_bayar'
    ];
}
