<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
    'sku_id',
    'location_id',
    'batch_number',
    'expired_date',
    'inbound_detail_id',
    'qty_stock',
    'qty_allocated'
];
public function getQtyAvailableAttribute()
{
    return $this->qty_stock - $this->qty_allocated;
}

public function sku()
{
    return $this->belongsTo(Sku::class);
}

public function location()
{
    return $this->belongsTo(Location::class);
}
public function inboundDetail()
{
    return $this->belongsTo(InboundDetail::class);
}
}