<?php

use Faker\Generator;
use Mtvs\EloquentHashids\Tests\Models\Child;
use Mtvs\EloquentHashids\Tests\Models\Item;

$factory->define(Child::class, function (Generator $faker) {
	return [
		'item_id' => factory(Item::class)->create()->id,
	];
});