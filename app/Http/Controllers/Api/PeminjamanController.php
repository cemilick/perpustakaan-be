<?php

namespace App\Http\Controllers\Api;

use App\Enum\PeminjamanStatus;
use App\Http\Controllers\BaseController;
use App\Models\Buku;
use App\Models\Peminjaman;
use Carbon\Carbon;

class PeminjamanController extends BaseController
{
    protected $class = Peminjaman::class;

    protected function beforeCreateHook($payload)
    {
        $buku = new Buku();
        $buku = $buku->findOrFail($payload['buku_id']);

        if ($buku->stok < 1) {
            return false;
        }

        return $payload;
    }

    protected function afterCreateHook($model)
    {
        $buku = new Buku();
        $buku = $buku->findOrFail($model->buku_id);
        $buku->stok -= 1;
        $buku->save();

        $model->status = 0;
        $model->save();

        return $model;
    }

    protected function transformIndexData($data)
    {
        $data->getCollection()->transform(function ($item) {
            $endDate = $item?->tanggal_kembali ?? new Carbon();
            $duration = date_diff(new Carbon($item->tanggal_pinjam), $endDate)->days;
            $item->lama_pinjam = "$duration hari";
            $item->harga = ($item->buku->harga ?? 0) * $duration;

            $item->status = PeminjamanStatus::getDescription($item->status);
            return $item;
        });

        return $data;
    }

    protected function afterUpdateHook($model)
    {
        if ($model->tanggal_kembali) {
            $buku = new Buku();
            $buku = $buku->findOrFail($model->buku_id);
            $buku->stok += 1;
            $buku->save();

            $model->status = 1;
            $model->save();
        }

        return $model;
    }
}
