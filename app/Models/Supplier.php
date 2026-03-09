<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name',
        'company_name',
        'phone',
        'email',
        'address'
    ];

    public $incrementing = false;
    protected $keyType = 'string';
}