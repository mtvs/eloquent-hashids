<?php

use Faker\Generator;
use Mtvs\EloquentHashids\Tests\Models\Comment;
use Mtvs\EloquentHashids\Tests\Models\Item;

$factory->define(Comment::class, function (Generator $faker) {
	return [
		'body' => $faker->paragraph(),

		'item_id' => function () {
			return factory(Item::class)->create();
		}
	];
});
