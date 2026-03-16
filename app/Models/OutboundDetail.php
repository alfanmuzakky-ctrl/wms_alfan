<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutboundDetail extends Model
{
    protected $fillable = [
        'outbound_id',
        'sku',
        'order_qty',
        'qty_allocated',
        'qty_picked',
        'qty_packed',
        'status'
    ];

    public function outbound()
{
    return $this->belongsTo(Outbound::class);
}

public function sku()
{
    return $this->belongsTo(Sku::class);
}

public function allocations()
{
    return $this->hasMany(OrderDetail::class);
}
}