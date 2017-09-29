<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

$factory->define(App\Customer::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'address' => $faker->streetAddress,
        'city' => $faker->city,
        'cep' => $faker->postcode,
        'district' => $faker->state,
        'phone' => $faker->phoneNumber
    ];
});

// @todo Fix order seed
$factory->define(App\Order::class, function (Faker\Generator $faker) {
    return [
        'total' => $faker->numberBetween(1000, 10000),
        'status' => 1,
        'customer_id' => $faker->randomElement(App\Customer::pluck('id')->toArray())
    ];
});