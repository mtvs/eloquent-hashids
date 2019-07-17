<?php

namespace Mtvs\EloquentHashids;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Vinkla\Hashids\Facades\Hashids;

Trait HasHashid 
{
	public function hashid()
	{
		return Hashids::connection($this->getHashidsConnection())
			->encode($this->getKey());
	}

	/**
	 * Find a model by its hashid
	 *
	 * @param Builder $query
	 * @param string $hashid
	 * @return Model|null
	 */
	public function scopeFindByHashid($query, $hashid)
	{
		$id = @Hashids::connection($this->getHashidsConnection())
			->decode($hashid)[0];

		if (! $id) {
			throw new InvalidArgumentException("Invalid hashid.");
			
		}

		return $query->find($id);
	}

	public function getHashidsConnection()
	{
		return config('hashids.default');
	}
}