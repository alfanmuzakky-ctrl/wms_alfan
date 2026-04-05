<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {

            $table->string('id')->primary();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->timestamps();

        });

        DB::table('customers')->insert([
            [
                'id' => 'EMADOS-BUAH-BATU',
                'name' => 'Emados Buah Batu',
                'company_name' => 'PT Emados Kebab Indonesia',
                'email' => 'emados@gmail.com',
                'phone' => '0812983127422',
                'address' => 'Jl. Buah Batu No.167, Turangga, Kec. Lengkong, Kota Bandung, Jawa Barat 40265',
                'contact_person' => 'Rifki',
                'contact_phone' => '0812983127422',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 'EMADOS-KOPO',
                'name' => 'Emados Kopo',
                'company_name' => 'PT Emados Kebab Indonesia',
                'email' => 'emados@gmail.com',
                'phone' => '0812344231564',
                'address' => 'Jl. Kopo Bihbul No.69, Sayati, Kec. Margahayu, Kabupaten Bandung, Jawa Barat 40228',
                'contact_person' => 'Hanny',
                'contact_phone' => '0812344231564',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
};