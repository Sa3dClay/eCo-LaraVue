<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'role_id' => 0,
            'role_name' => 'Admin',
            'role_slug' => 'admin'
        ]);
        
        DB::table('roles')->insert([
            'role_id' => 1,
            'role_name' => 'Customer',
            'role_slug' => 'customer'
        ]);        
    }
}
