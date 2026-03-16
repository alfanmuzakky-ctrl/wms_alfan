<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutboundAllocation extends Model
{
public function outboundDetail()
{
    return $this->belongsTo(OutboundDetail::class,'outbound_detail_id');
}
}