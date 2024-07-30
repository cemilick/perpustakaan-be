<?php

namespace App\Models;

class Customer extends BaseModel
{
    protected $fillable = [
        'created_by',
        'updated_by',
        'nama',
        'tgl_lahir'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->no_anggota = self::generateNoAnggota();
        });
    }

    protected static function generateNoAnggota()
    {
        $customer = new Customer();
        $latest = $customer->newQuery()
            ->whereNotNull('no_anggota')
            ->orderByDesc('no_anggota')
            ->first('no_anggota');

        $latestNumber = intval($latest->no_anggota);

        return str_pad($latestNumber + 1, 5, '0', STR_PAD_LEFT);
    }
}
