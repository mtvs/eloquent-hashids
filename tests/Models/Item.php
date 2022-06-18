<?php

namespace Mtvs\EloquentHashids\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;
use Mtvs\EloquentHashids\Tests\Models\Comment;

class Item extends Model
{
	use HasHashid, HashidRouting, SoftDeletes;

	protected $guarded = [];

	public function comments()
	{
		return $this->hasMany(Comment::class);
	}
}