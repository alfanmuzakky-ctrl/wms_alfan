<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outbound extends Model
{
    protected $fillable = [
        'id',
        'customer_id',
        'status'
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function customer()
{
    return $this->belongsTo(Customer::class);
}

public function details()
{
    return $this->hasMany(OutboundDetail::class);
}
}