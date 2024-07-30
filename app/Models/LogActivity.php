<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class LogActivity extends BaseModel
{
    use HasUuids;

    protected $withoutAuthor = true;

    protected $fillable = [
        'relation_id',
        'relation_type',
        'action',
        'content'
    ];
}
