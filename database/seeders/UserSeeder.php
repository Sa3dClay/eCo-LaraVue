<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
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
            'password' => Hash::make('password')
        ]);
    }
}