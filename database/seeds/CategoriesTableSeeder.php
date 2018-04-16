<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Category::query()->create(['name' => 'Lanche']);
        App\Category::query()->create(['name' => 'Festa']);
        App\Category::query()->create(['name' => 'Especiais']);
        App\Category::query()->create(['name' => 'Gecepel']);
        App\Category::query()->create(['name' => 'Miniaturas']);
    }
}
