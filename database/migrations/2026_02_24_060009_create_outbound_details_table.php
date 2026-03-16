<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outbound_details', function (Blueprint $table) {

            $table->id();

            $table->string('outbound_id');
            $table->string('sku');

            $table->integer('order_qty');

            $table->integer('qty_allocated')->default(0);
            $table->integer('qty_picked')->default(0);
            $table->integer('qty_packed')->default(0);

            $table->string('status')->default('CREATE');

            $table->timestamps();

            $table->foreign('outbound_id')
                  ->references('id')
                  ->on('outbounds')
                  ->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outbound_details');
    }
};