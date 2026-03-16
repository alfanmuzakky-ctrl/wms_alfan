<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inbounds', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('supplier_id');

            $table->enum('status', [
                'CREATE',
                'PARTIALLY_RECEIVED',
                'RECEIVED',
                'CLOSE'
            ])->default('CREATE');

            $table->timestamps();

            $table->foreign('supplier_id')
                  ->references('id')
                  ->on('suppliers')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inbounds');
    }
};