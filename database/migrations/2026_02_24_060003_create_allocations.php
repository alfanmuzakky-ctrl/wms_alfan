<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('allocations', function (Blueprint $table) {

$table->string('id')->primary();

$table->string('outbound_detail_id');

$table->string('inventory_id');

$table->integer('qty');

$table->timestamps();

});
    }

    public function down()
    {
        Schema::dropIfExists('allocations');
    }
};