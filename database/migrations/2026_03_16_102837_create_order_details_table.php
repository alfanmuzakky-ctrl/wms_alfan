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

            $table->string('location');

            $table->integer('qty_allocated')->default(0);
            $table->integer('qty_picked')->default(0);

            $table->string('status')->default('ALLOCATED');

            $table->timestamps();

            $table->foreign('outbound_detail_id')
                  ->references('id')
                  ->on('outbound_details')
                  ->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};