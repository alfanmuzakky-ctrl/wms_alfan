<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inbound_details', function (Blueprint $table) {
            $table->id();

            $table->string('inbound_id');
            $table->string('sku_id');

            $table->integer('qty');
            $table->integer('received_qty')->default(0);

            $table->string('batch_number')->nullable();
            $table->date('expired_date')->nullable();

            $table->enum('status', [
                'CREATE',
                'PARTIAL',
                'RECEIVED',
                'CLOSE'
            ])->default('CREATE');

            $table->timestamps();

            $table->foreign('inbound_id')
                  ->references('id')
                  ->on('inbounds')
                  ->onDelete('cascade');

            $table->foreign('sku_id')
                  ->references('id')
                  ->on('skus')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inbound_details');
    }
};