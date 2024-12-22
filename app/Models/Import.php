<?php

namespace App\Models;

use App\ImportStatus;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    protected $fillable = [
        'import_type',
        'file_key',
        'file_name',
        'status',
    ];

    protected $casts = [
        'status' => ImportStatus::class,
    ];
}
