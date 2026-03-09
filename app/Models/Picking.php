<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Picking extends Model
{
    use HasFactory;

    protected $fillable = [
        'allocation_id',
        'qty_picked',
        'picked_by'
    ];

    public function allocation()
    {
        return $this->belongsTo(Allocation::class,'allocation_id');
    }

    public function packings()
    {
        return $this->hasMany(Packing::class,'picking_id');
    }
}