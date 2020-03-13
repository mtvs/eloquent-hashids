<?php

use Faker\Generator;
use Mtvs\EloquentHashids\Tests\Models\Item;

$factory->define(Item::class, function (Generator $faker) {
	return [
		'slug' => $faker->unique()->word()
	];
});