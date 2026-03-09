<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryLocation extends Model
{
    protected $fillable = [
        'sku_id',
        'location_id',
        'batch_number',
        'expired_date',
        'qty'
    ];
}