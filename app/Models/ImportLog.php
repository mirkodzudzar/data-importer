<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    protected $fillable = [
        'import_type',
        'row_number',
        'row_data',
        'error_column',
        'error_message',
        'status'
    ];

    protected $casts = [
        'row_data' => 'array'
    ];
}
