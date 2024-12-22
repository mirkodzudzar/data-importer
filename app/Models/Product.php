<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'import_id',
        'sku',
        'item_description',
        'total_price'
    ];
}
