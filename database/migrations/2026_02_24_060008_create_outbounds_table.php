<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outbounds', function (Blueprint $table) {

            $table->string('id')->primary();

            $table->string('customer_id');
            $table->foreign('customer_id')
                  ->references('id')
                  ->on('customers')
                  ->onDelete('cascade');

            $table->string('status')->default('CREATE');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outbounds');
    }
};