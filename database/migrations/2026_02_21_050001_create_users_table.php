<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('account')->unique();
            $table->string('password');
            $table->enum('role', ['Admin', 'Operator']);
            $table->timestamps();
        });

        // 🔥 INSERT DEFAULT USERS
        DB::table('users')->insert([
            [
                'account' => 'admin',
                'password' => Hash::make('@123456'),
                'role' => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'account' => 'operator',
                'password' => Hash::make('@123456'),
                'role' => 'Operator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};