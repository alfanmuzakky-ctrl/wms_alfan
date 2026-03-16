<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{

protected $fillable = [
'outbound_detail_id',
'location',
'batch_number',
'expired_date',
'qty_allocated',
'qty_picked',
'status'
];

public function outboundDetail()
{
return $this->belongsTo(OutboundDetail::class);
}

}