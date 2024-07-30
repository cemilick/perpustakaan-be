<?php

namespace App\Enum;

enum PeminjamanStatus: int
{
    case DIPINJAM = 0;
    case DIKEMBALIKAN = 1;

    public static function getDescription($status)
    {
        $description = [
            self::DIPINJAM->value => 'Sedang Dipinjam',
            self::DIKEMBALIKAN->value => 'Sudah Dikembalikan',
        ];

        return $description[$status] ?? '-';
    }
}