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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {

    static $password;

    /*$faker->addProvider(new Faker\Provider\ru_Ru\Person($faker));
    $faker->addProvider(new Faker\Provider\ru_Ru\Address($faker));
    $faker->addProvider(new Faker\Provider\ru_Ru\PhoneNumber($faker));*/

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'city' => numberBetween($min = 1, $max = 10),
        'photo' => $faker->imageUrl(40, 40),

        'phone' => $faker->phoneNumber,
    ];
});

$factory->define(App\Pupils::class, function (Faker\Generator $faker) {
    return [
        'firstName' => $faker->name,
        'secondName' => $faker->name,
        'birthDate' => $faker->date($format = 'Y-m-d', $max = '2000-01-01'),
        'city' => $faker->numberBetween($min = 1, $max = 10),
        'phone' => $faker->e164PhoneNumber,
    ];
});

$factory->define(App\Teachers::class, function (Faker\Generator $faker) {
    return [
        'firstName' => $faker->name,
        'secondName' => $faker->name,
        'birthDate' => $faker->date($format = 'Y-m-d', $max = '2000-01-01'),
        'phone' => $faker->e164PhoneNumber,
    ];
});
$factory->define(App\Groups::class, function (Faker\Generator $faker) {
    $startDate = $faker->date($format = 'Y-m-d', $min = '2013-11-11');
    return [
        'name' => $faker->company,
        'type_id' => $faker->numberBetween($min = 1, $max = 5),
        'status' => $faker->numberBetween($min = 1, $max = 5),
        'city' => $faker->numberBetween($min = 1, $max = 10),
        'teachers_id' => $faker->numberBetween($min = 1, $max = 10),
        'startDate' => $startDate,
        'endDate' => $faker->date($format = 'Y-m-d', $min = $startDate),
    ];
});
$factory->define(App\PupilsAndGroups::class, function (Faker\Generator $faker) {
    return [
        'pupils_id' => $faker->numberBetween($min = 1, $max = 20),
        'groups_id' => $faker->numberBetween($min = 1, $max = 40),
    ];
});
