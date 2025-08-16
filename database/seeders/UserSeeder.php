<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        $now = now();
        DB::table('users')->insert([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'email_verified_at' => null,
            'password' => Hash::make('User@123'), 
            'remember_token' => null,
            'created_at' => $now,
            'updated_at' => $now,
            'role' => 'user',
        ]);
    }
}