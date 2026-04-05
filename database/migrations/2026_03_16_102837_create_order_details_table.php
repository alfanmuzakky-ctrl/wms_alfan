<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
                    Schema::create('order_details', function (Blueprint $table) {

                $table->id();

                $table->unsignedBigInteger('outbound_detail_id');

                // 🔥 TAMBAHAN PENTING
                $table->unsignedBigInteger('inventory_id')->nullable();

                $table->string('location');

                $table->string('batch_number')->nullable();
                $table->date('expired_date')->nullable();

                $table->integer('qty_allocated')->default(0);
                $table->integer('qty_picked')->default(0);

                $table->string('status')->default('ALLOCATED');

                $table->timestamps();

                $table->foreign('outbound_detail_id')
                    ->references('id')
                    ->on('outbound_details')
                    ->onDelete('cascade');

                // 🔥 RELASI KE INVENTORY
                $table->foreign('inventory_id')
                    ->references('id')
                    ->on('inventories')
                    ->onDelete('set null');
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};