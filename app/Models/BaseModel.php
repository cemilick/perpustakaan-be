<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaseModel extends Model
{
    use HasUuids;

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = Str::orderedUuid();
            $model->created_by = auth()->id();
            $model->updated_by = auth()->id();
        });
    }

    public function getAllData()
    {
        return $this->all();
    }

    public function getDataById($id)
    {
        return $this->findOrFail($id);
    }
}
