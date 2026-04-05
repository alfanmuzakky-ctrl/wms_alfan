<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {

            $table->string('id')->primary();
            $table->string('name');
            $table->string('company_name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address');
            $table->timestamps();

        });

       
        DB::table('suppliers')->insert([

            [
                'id' => 'DUA-PUTRA',
                'name' => 'PT Dua Putra Perkasa Pratama',
                'company_name' => 'Dua Putra Perkasa Pratama',
                'phone' => '02129555555',
                'email' => 'duaputra@gmail.com',
                'address' => 'Jl. Baru Cipendawa No. 88 , Kawasan Industri Cipendawa, Bekasi 17117',
                'created_at' => now(),
                'updated_at' => now()
            ],
    
            [
                'id' => 'DEANYS-PUTRA',
                'name' => 'PT Deanys Putra Berdikari',
                'company_name' => 'Deanys Putra Berdikari',
                'phone' => '085723660682',
                'email' => 'deanysputraberdikari@gmail.com',
                'address' => 'Jl. Cijagra No. 19A Bandung, Jawa Barat',
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'id' => 'ELEFIN',
                'name' => 'PT Vigo Frozen Indonesia',
                'company_name' => 'Vigo Frozen Indonesia',
                'phone' => '082123847731',
                'email' => 'vigofrozen19@gmail.com',
                'address' => 'Jl. Cikutra No. 40 Bandung, Jawa Barat',
                'created_at' => now(),
                'updated_at' => now()
            ]

        ]);
    }

    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
};