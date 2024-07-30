<?php

namespace App\Http\Controllers\Api;

use App\Enum\PeminjamanStatus;
use App\Http\Controllers\BaseController;
use App\Models\Buku;
use App\Models\LogActivity;
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

        LogActivity::create([
            'relation_id' => $buku->id,
            'relation_type' => Buku::class,
            'action' => 'UPDATE',
            'content' => "$buku->judul Dipinjam oleh " . $model->customer->nama . " pada tanggal " . $model->tanggal_pinjam
        ]);

        return $model;
    }

    protected function transformIndexData($data)
    {
        $transformedData = collect($data['data']);

        $transformedData = $transformedData->transform(function ($item) {
            $endDate = $item?->tanggal_kembali ?? new Carbon();
            $duration = date_diff(new Carbon($item->tanggal_pinjam), new Carbon($endDate))->days;
            $item->lama_pinjam = "$duration hari";
            $item->harga = ($item->buku->harga ?? 0) * $duration;

            $item->status = PeminjamanStatus::getDescription($item->status);
            return $item;
        });

        $data['data'] = $transformedData;

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

            LogActivity::create([
                'relation_id' => $buku->id,
                'relation_type' => Buku::class,
                'action' => 'UPDATE',
                'content' => "$buku->judul Dikembalikan oleh " . $model->customers->nama . " pada tanggal " . $model->tanggal_kembali
            ]);
        }

        return $model;
    }

    public function getPeminjamanOptions()
    {
        $peminjaman = new Peminjaman();
        $peminjaman = $peminjaman->getAllData()->where('status', 0);
        $transformed = collect($peminjaman)->map(function ($item) {
            $date = explode('-', $item->tanggal_pinjam);
            $date = Carbon::createFromDate($date[0], $date[1], $date[2], 'Asia/Jakarta');
            $date = $date->format('d F Y');
            return [
                'value' => $item->id,
                'label' => $item->buku->judul . " - " . $item->customer->nama . " - " . $date
            ];
        })->toArray();

        return $this->responseJson(array_merge([
            [
                'value' => null,
                'label' => 'Pilih Transaksi'
            ]
        ], $transformed));
    }
}
