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

        // AUTO INSERT SAMPLE DATA
        DB::table('suppliers')->insert([

            [
                'id' => 'SUP001',
                'name' => 'PT Indo Food',
                'company_name' => 'Indo Food Distribution',
                'phone' => '021111111',
                'email' => 'indfood@test.com',
                'address' => 'Jakarta',
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'id' => 'SUP002',
                'name' => 'PT Dairy Milk',
                'company_name' => 'Dairy Milk Indonesia',
                'phone' => '021222222',
                'email' => 'dairymilk@test.com',
                'address' => 'Bandung',
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'id' => 'SUP003',
                'name' => 'PT Frozen Food',
                'company_name' => 'Frozen Food Supply',
                'phone' => '021333333',
                'email' => 'frozen@test.com',
                'address' => 'Surabaya',
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