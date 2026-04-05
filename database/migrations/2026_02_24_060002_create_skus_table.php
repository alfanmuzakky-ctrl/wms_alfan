<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('skus', function (Blueprint $table) {

            $table->string('id')->primary();
            $table->string('alternative_code')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('category', ['Dry','Cool','Chilled','Sub-Zero','Frozen']);
            $table->enum('uom', ['Pcs','Pack','Carton','Bag']);
            $table->timestamps();

        });

       
        DB::table('skus')->insert([

            [
                'id' => 'WHOLE-CHICKEN-06',
                'alternative_code' => '',
                'name' => 'Ayam Karkas Marinasi UK 0.6-0.7',
                'description' => 'Ayam Karkas Marinasi UK 0.6-0.7 25e',
                'category' => 'Frozen',
                'uom' => 'Bag',
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'id' => 'WHOLE-CHICKEN-07',
                'alternative_code' => '',
                'name' => 'Ayam Karkas Marinasi UK 0.7-0.8',
                'description' => 'Ayam Karkas Marinasi UK 0.7-0.8 25e',
                'category' => 'Frozen',
                'uom' => 'Bag',
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'id' => 'WHOLE-CHICKEN-1.1',
                'alternative_code' => '',
                'name' => 'Ayam Karkas Marinasi UK 1.1',
                'description' => 'Ayam Karkas Marinasi UK 1.1 20e',
                'category' => 'Frozen',
                'uom' => 'Bag',
                'created_at' => now(),
                'updated_at' => now()
            ]
            ,

            [
                'id' => 'BASMATI-RICE',
                'alternative_code' => '',
                'name' => 'Beras Basmati Shukriya 1kg',
                'description' => 'Beras Basmati Shukriya 1kg',
                'category' => 'Dry',
                'uom' => 'Bag',
                'created_at' => now(),
                'updated_at' => now()
            ]

        ]);
    }

    public function down()
    {
        Schema::dropIfExists('skus');
    }
};