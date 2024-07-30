<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasUuids;

    public function getAllData()
    {
        return $this->all();
    }

    public function getDataById($id)
    {
        return $this->findOrFail($id);
    }
}
