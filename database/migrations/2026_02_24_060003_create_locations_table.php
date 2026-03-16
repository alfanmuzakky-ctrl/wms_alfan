<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->string('id')->primary(); // location_id
            $table->string('zone_group')->nullable();
            $table->string('location_category')->nullable();
            $table->enum('location_attribute', ['Active','Quarantine','Non-Active','Staging'])->default('Active');
            $table->timestamps();
        });

        // Default Locations
        DB::table('locations')->insert([
            [
                'id' => 'INB-STATION',
                'zone_group' => 'INBOUND',
                'location_category' => 'Station',
                'location_attribute' => 'Staging',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 'OUT-STATION',
                'zone_group' => 'OUTBOUND',
                'location_category' => 'Station',
                'location_attribute' => 'Staging',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 'LOCATION-01',
                'zone_group' => 'FROZEN',
                'location_category' => 'Frozen',
                'location_attribute' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
};