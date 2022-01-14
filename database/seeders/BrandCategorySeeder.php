<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // brand_id 6
        DB::table('brands')->insert([
            'name' => 'Lenovo'
        ]);
        // brand_id 7
        DB::table('brands')->insert([
            'name' => 'Samsung'
        ]);
        // category_id 3
        DB::table('categories')->insert([
            'name' => 'Laptop'
        ]);
        // category_id 4
        DB::table('categories')->insert([
            'name' => 'Mobile'
        ]);
        // category_id 5
        DB::table('categories')->insert([
            'name' => 'Sport'
        ]);
        // category_id 6
        DB::table('categories')->insert([
            'name' => 'Shoes'
        ]);
        // Apple, Electronics
        DB::table('brand_category')->insert([
            'brand_id'      => '1',
            'category_id'   => '1'
        ]);
        // Apple, Laptop
        DB::table('brand_category')->insert([
            'brand_id'      => '1',
            'category_id'   => '3'
        ]);
        // Apple, Mobile
        DB::table('brand_category')->insert([
            'brand_id'      => '1',
            'category_id'   => '4'
        ]);
        // Dell, Electronics
        DB::table('brand_category')->insert([
            'brand_id'      => '2',
            'category_id'   => '1'
        ]);
        // Dell, Laptop
        DB::table('brand_category')->insert([
            'brand_id'      => '2',
            'category_id'   => '3'
        ]);
        // HP, Electronics
        DB::table('brand_category')->insert([
            'brand_id'      => '3',
            'category_id'   => '1'
        ]);
        // HP, Laptop
        DB::table('brand_category')->insert([
            'brand_id'      => '3',
            'category_id'   => '3'
        ]);
        // Nike, Fashion
        DB::table('brand_category')->insert([
            'brand_id'      => '4',
            'category_id'   => '2'
        ]);
        // Nike, Sport
        DB::table('brand_category')->insert([
            'brand_id'      => '4',
            'category_id'   => '5'
        ]);
        // Nike, Shoes
        DB::table('brand_category')->insert([
            'brand_id'      => '4',
            'category_id'   => '6'
        ]);
        // Adidas, Fashion
        DB::table('brand_category')->insert([
            'brand_id'      => '5',
            'category_id'   => '2'
        ]);
        // Adidas, Sport
        DB::table('brand_category')->insert([
            'brand_id'      => '5',
            'category_id'   => '5'
        ]);
        // Adidas, Shoes
        DB::table('brand_category')->insert([
            'brand_id'      => '5',
            'category_id'   => '6'
        ]);
        // Lenovo, Electronics
        DB::table('brand_category')->insert([
            'brand_id'      => '6',
            'category_id'   => '1'
        ]);
        // Lenovo, Laptop
        DB::table('brand_category')->insert([
            'brand_id'      => '6',
            'category_id'   => '3'
        ]);
        // Samsung, Electronics
        DB::table('brand_category')->insert([
            'brand_id'      => '7',
            'category_id'   => '1'
        ]);
        // Samsung, Mobile
        DB::table('brand_category')->insert([
            'brand_id'      => '7',
            'category_id'   => '4'
        ]);
    }
}
