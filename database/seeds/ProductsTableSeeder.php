<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Product::class)->create(['name' => 'Salgado de carne']);
        factory(App\Product::class)->create(['name' => 'Salgado de frango']);
        factory(App\Product::class)->create(['name' => 'Salgado de frios']);
    }
}
