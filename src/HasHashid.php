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
		$id = static::hashidToId($hashid);

		return $query->find($id);
	}

	/**
	 * Decode the hashid to the id
	 *
	 * @param string $hashid
	 * @return int
	 *
	 * @throws InvalidArgumentException
	 */
	public static function hashidToId($hashid)
	{
		$id = @Hashids::connection((new static)->getHashidsConnection())
			->decode($hashid)[0];

		if (! $id) {
			throw new InvalidArgumentException("Invalid hashid.");
		}

		return $id;
	}

	public function getHashidsConnection()
	{
		return config('hashids.default');
	}
}