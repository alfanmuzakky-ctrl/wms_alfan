<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sku extends Model
{
    protected $table = 'skus';

    protected $fillable = [
    'id',
    'alternative_code',
    'name',
    'description',
    'category',
    'uom'
];

    public $incrementing = false; // karena ID pakai varchar
    protected $keyType = 'string'; // tipe primary key string
}