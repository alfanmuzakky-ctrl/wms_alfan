<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InboundDetail extends Model
{
    protected $fillable = [
        'inbound_id',
        'sku_id',
        'qty',
        'received_qty',
        'batch_number',
        'expired_date',
        'status'
    ];

   public function inbound()
{
    return $this->belongsTo(Inbound::class);
}

public function sku()
{
    return $this->belongsTo(Sku::class);
}

}