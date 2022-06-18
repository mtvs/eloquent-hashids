<?php

namespace Mtvs\EloquentHashids\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\Tests\Models\Comment;
use Mtvs\EloquentHashids\Tests\Models\Item;

class Vendor extends Model
{
	public function items()
	{
		return $this->hasMany(Item::class);
	}

	public function comments()
	{
		return $this->hasManyThrough(Comment::class, Item::class);
	}
}
