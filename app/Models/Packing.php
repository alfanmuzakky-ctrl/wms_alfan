<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packing extends Model
{
    use HasFactory;

    protected $fillable = [
        'picking_id',
        'qty_packed',
        'packed_by'
    ];

    public function picking()
    {
        return $this->belongsTo(Picking::class,'picking_id');
    }
}