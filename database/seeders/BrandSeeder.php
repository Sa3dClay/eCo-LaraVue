<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('brands')->insert([
            'name' => 'Apple'
        ]);
        DB::table('brands')->insert([
            'name' => 'Dell'
        ]);
        DB::table('brands')->insert([
            'name' => 'HP'
        ]);
        DB::table('brands')->insert([
            'name' => 'Nike'
        ]);
        DB::table('brands')->insert([
            'name' => 'Adidas'
        ]);
    }
}
