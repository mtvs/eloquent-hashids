<?php

namespace Mtvs\EloquentHashids;

use Vinkla\Hashids\Facades\Hashids;

Trait HasHashid 
{
	public function hashid()
	{
		return Hashids::connection($this->getHashidsConnection())
			->encode($this->getKey());
	}

	public function getHashidsConnection()
	{
		return config('hashids.default');
	}
}