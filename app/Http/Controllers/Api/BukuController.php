<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Buku;

class BukuController extends BaseController
{
    protected $class = Buku::class;

    public function getBukuOptions()
    {
        $buku = new Buku();
        $buku = $buku->getOptions();

        return $this->responseJson(array_merge([
            [
                'value' => null,
                'label' => 'Pilih Buku'
            ]
        ], collect($buku)->map(function ($item) {
            return [
                'value' => $item->id,
                'label' => $item->judul
            ];
        })->toArray()));
    }
}
