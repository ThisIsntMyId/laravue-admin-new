<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Laravue\Models\Card;
use App\Laravue\Models\Category;
use Faker\Generator as Faker;

$factory->define(Card::class, function (Faker $faker) {
    $category = Category::all()->random();
    return [
        'name' => $faker->name,
        'description' => $faker->sentence,
        'price' => $faker->numberBetween($min = 1000, $max = 9000),
        'category' => $category->id,
    ];
});
