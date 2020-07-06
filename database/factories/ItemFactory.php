<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use App\Models\Item;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'title'=>$faker->title,
        'description'=>$faker->realText(200),
        'active'=>$faker->boolean(20)
    ];
});
