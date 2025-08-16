<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $now = now();
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => null,
            'password' => Hash::make('Admin@123'), 
            'remember_token' => null,
            'created_at' => $now,
            'updated_at' => $now,
            'role' => 'admin',
        ]);
    }
}