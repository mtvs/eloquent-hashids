<?php

namespace Mtvs\EloquentHashids\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class Item extends Model
{
	use HasHashid;
	use HashidRouting;

	protected $guarded = [];
}