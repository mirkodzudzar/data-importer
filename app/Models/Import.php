<?php

namespace App\Models;

use App\ImportStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Import extends Model
{
    protected $fillable = [
        'user_id',
        'import_type',
        'file_key',
        'file_name',
        'status',
    ];

    protected $casts = [
        'status' => ImportStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
