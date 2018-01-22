<?php

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
        $this->call('CustomersTableSeeder');
        $this->call('ProductsTableSeeder');

        factory(\App\User::class)->create([
            'email' => 'eduardoumpierre@hotmail.com',
            'password' => app('hash')->make('123')
        ]);
    }
}
