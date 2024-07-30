<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait HasResponseJson
{
    protected function responseJson($data)
    {
        return new JsonResponse([
            'data' => $data,
            'status' => 200,
        ]);
    }
}