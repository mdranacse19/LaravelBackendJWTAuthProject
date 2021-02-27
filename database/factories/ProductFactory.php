<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Product::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'description' => $faker->address,
        'price' => $faker->randomFloat($nbMaxDecimals = 1, $min = 0, $max = 100),
        'image' => $faker->imageUrl($width = 640, $height = 480) // 'http://lorempixel.com/640/480/',
    ];
});
