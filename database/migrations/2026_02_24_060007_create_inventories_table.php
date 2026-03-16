<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    Schema::create('inventories', function (Blueprint $table) {
    $table->id();
    $table->string('sku_id');
    $table->string('location_id');
    $table->string('batch_number')->nullable();
    $table->date('expired_date')->nullable();
    $table->integer('qty_stock')->default(0);
    $table->integer('qty_allocated')->default(0);
    $table->timestamps();

    $table->foreign('sku_id')->references('id')->on('skus')->onDelete('cascade');
    $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
});
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventories');
    }
};
