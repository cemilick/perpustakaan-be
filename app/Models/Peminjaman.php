<?php

namespace App\Models;

class Peminjaman extends BaseModel
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'buku_id',
        'customer_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'harga',
        'created_by',
        'updated_by'
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
