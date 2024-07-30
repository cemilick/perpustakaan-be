<?php

namespace App\Models;

class Buku extends BaseModel
{
    protected $fillable = [
        'judul',
        'penerbit',
        'jumlah_halaman',
        'stok',
        'created_by',
        'updated_by'
    ];
}
