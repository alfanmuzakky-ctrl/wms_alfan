<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'locations';

    protected $fillable = [
        
        'id',
        'zone_group',
        'location_category',
        'location_attribute',
    ];

    public $incrementing = false;
    protected $keyType = 'string';
}