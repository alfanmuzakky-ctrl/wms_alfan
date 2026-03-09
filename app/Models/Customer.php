<?php

namespace App\Models;

/* Imports */
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /* Configuration */
    protected $table = 'customers';
    protected $primaryKey = 'id';

    // Konfigurasi Primary Key String
    public $incrementing = false; 
    protected $keyType = 'string';

    /* Mass Assignment */
    protected $fillable = [
        'id',
        'name',
        'company_name',
        'email',
        'phone',
        'address',
        'contact_person',
        'contact_phone'

    ];

    /* Relationships */

    /**
     * Relasi ke Outbound (Satu Customer memiliki banyak Outbound)
     */
    public function outbounds()
    {
        return $this->hasMany(Outbound::class, 'customer_id', 'id');
    }
}