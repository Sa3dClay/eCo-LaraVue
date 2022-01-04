<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'role'  => '0',
            'name'  => 'Admin',
            'email' => 'admin@eco.com',
            'email_verified_at' => '2022-01-01 22:00:00',
            'password' => Hash::make('P@ssw0rd')
        ]);
    }
}
