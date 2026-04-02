<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
User::create([
    'account' => 'admin',
    'password' => Hash::make('123456'),
    'role' => 'Admin'
]);

User::create([
    'account' => 'operator',
    'password' => Hash::make('123456'),
    'role' => 'Operator'
]);
