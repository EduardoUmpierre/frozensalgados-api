<?php

use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Customer::class, 30)->create()->each(function ($customer) {
            $customer->orders()->saveMany(factory(App\Order::class, 2)->make());
        });
    }
}
