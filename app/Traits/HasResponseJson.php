<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait HasResponseJson
{
    protected function responseJson($data, $status = 200)
    {
        return new JsonResponse([
            'data' => $data,
            'status' => $status,
        ]);
    }
}