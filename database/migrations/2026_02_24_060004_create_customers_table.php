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
                'id' => 'CUST001',
                'name' => 'Tokopedia',
                'company_name' => 'Tokopedia Warehouse',
                'email' => 'tokopedia@test.com',
                'phone' => '08123456789',
                'address' => 'Jakarta',
                'contact_person' => 'Budi',
                'contact_phone' => '08123456789',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 'CUST002',
                'name' => 'Shopee',
                'company_name' => 'Shopee Indonesia',
                'email' => 'shopee@test.com',
                'phone' => '0811111111',
                'address' => 'Jakarta',
                'contact_person' => 'Andi',
                'contact_phone' => '0811111111',
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