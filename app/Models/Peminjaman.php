<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function getAllData()
    {
        return $this->with(['buku', 'customer'])->orderByDesc('updated_at')->get('*');
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
