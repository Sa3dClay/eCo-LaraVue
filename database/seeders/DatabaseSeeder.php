<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminSeeder::class);
        $this->call(RolesSeeder::class);
        
        $this->call(BrandSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(BrandCategorySeeder::class);
    }
}
