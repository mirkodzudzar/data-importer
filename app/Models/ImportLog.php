<?php

namespace App\Models;

use App\ImportLogStatus;
use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    protected $fillable = [
        'file_key',
        'file_name',
        'import_type',
        'row_number',
        'row_data',
        'error_column',
        'error_message',
        'status'
    ];

    protected $casts = [
        'row_data' => 'array',
        'status' => ImportLogStatus::class,
    ];
}
