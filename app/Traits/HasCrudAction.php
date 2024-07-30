<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait HasCrudAction
{
    protected function getBasePayload($isCreate = false)
    {
        $basePayload = [
            'updated_by' => auth()->id(),
            'updated_at' => Carbon::now(),
        ];

        $createdPayload = [
            'id' => Str::orderedUuid()->toString(),
            'created_by' => auth()->id(),
            'created_at' => Carbon::now(),
        ];

        return $isCreate ? array_merge($createdPayload, $basePayload) : $basePayload;
    }

    protected function populatePayload(array $data, $isCreate = false)
    {
        $payload = Arr::only($data, $this->model->getFillable());
        $payload = array_merge($this->getBasePayload($isCreate), $payload);

        return $payload;
    }
}