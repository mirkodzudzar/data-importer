<?php

namespace App\Models;

use App\ImportLogStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportLog extends Model
{
    protected $fillable = [
        'import_id',
        'file_key',
        'file_name',
        'import_type',
        'row_number',
        'error_column_value',
        'error_column',
        'error_message',
        'status'
    ];

    protected $casts = [
        'status' => ImportLogStatus::class,
    ];

    public function import(): BelongsTo
    {
        return $this->belongsTo(Import::class);
    }
}
