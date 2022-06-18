<?php

namespace Mtvs\EloquentHashids\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class ItemWithCustomRouteKeyName extends Model
{
	use HasHashid, HashidRouting;

	protected $guarded = [];

	protected $table = 'items';

	public function getRouteKeyName()
	{
		return 'slug';
	}
}