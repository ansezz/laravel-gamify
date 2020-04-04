<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\Ansezz\Gamify\GamifyLevel::class, function (Faker $faker) {
    return [
        "name" => $faker->text(50),
    ];
});
