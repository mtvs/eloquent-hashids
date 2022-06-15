<?php

use Faker\Generator;
use Mtvs\EloquentHashids\Tests\Models\Vendor;

$factory->define(Vendor::class, function (Generator $faker) {
	return [
		'name' => $faker->name,
	];
});
