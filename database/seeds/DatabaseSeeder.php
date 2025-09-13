<?php

use Database\Seeders\AssociationRulesSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\CategoriesSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(CategoriesSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(AssociationRulesSeeder::class);
    }
}
