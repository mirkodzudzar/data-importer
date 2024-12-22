<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'import_id',
        'order_date',
        'channel',
        'sku',
        'item_description',
        'origin',
        'so_num',
        'cost',
        'shipping_cost',
        'total_price'
    ];
}
