<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $fillable = [
        'kost_id',
        'nomor_kamar',
        'harga',
        'status'
    ];
}
