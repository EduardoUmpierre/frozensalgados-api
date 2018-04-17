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
        factory(App\Category::class)->create(['name' => 'Lanche']);
        factory(App\Category::class)->create(['name' => 'Festa']);
        factory(App\Category::class)->create(['name' => 'Especiais']);
        factory(App\Category::class)->create(['name' => 'Gecepel']);
        factory(App\Category::class)->create(['name' => 'Miniaturas']);
    }
}
