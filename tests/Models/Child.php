<?php

namespace Mtvs\EloquentHashids\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class Child extends Model
{
	use HasHashid;
	use HashidRouting;

	protected $guarded = [];

    public function Item(){
        return $this->belongsTo(Item::class);
    }
}