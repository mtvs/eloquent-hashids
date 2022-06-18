<?php

use Faker\Generator;
use Mtvs\EloquentHashids\Tests\Models\Item;
use Mtvs\EloquentHashids\Tests\Models\Vendor;

$factory->define(Item::class, function (Generator $faker) {
	return [
		'slug' => $faker->unique()->word(),
		'vendor_id' => function() {
			return factory(Vendor::class)->create();
		}
	];
});

$factory->state(Item::class, 'softDeleted', [
	'deleted_at' => now(),
]);