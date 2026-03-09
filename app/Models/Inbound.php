<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inbound extends Model
{
    protected $fillable = [
        'id',
        'supplier_id',
        'status'
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function details()
    {
        return $this->hasMany(InboundDetail::class);
    }
    

    public function supplier()
{
    return $this->belongsTo(Supplier::class);
}
}