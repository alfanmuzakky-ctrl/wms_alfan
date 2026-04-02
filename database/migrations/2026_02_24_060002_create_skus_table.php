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
                'id' => 'SKU001',
                'alternative_code' => 'ALT001',
                'name' => 'Instant Noodle',
                'description' => 'Dry food product',
                'category' => 'Dry',
                'uom' => 'Pack',
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'id' => 'SKU002',
                'alternative_code' => 'ALT002',
                'name' => 'Milk UHT',
                'description' => 'Chilled dairy product',
                'category' => 'Chilled',
                'uom' => 'Carton',
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'id' => 'SKU003',
                'alternative_code' => 'ALT003',
                'name' => 'Frozen Chicken',
                'description' => 'Frozen meat product',
                'category' => 'Frozen',
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