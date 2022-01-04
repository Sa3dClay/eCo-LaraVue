<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DeleteUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('carts')->truncate();
        
        // disable foreign key
        Schema::disableForeignKeyConstraints();

        DB::table('users')->truncate();

        // enable foreign key
        Schema::enableForeignKeyConstraints();
    }
}
