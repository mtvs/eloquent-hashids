<?php

namespace Mtvs\EloquentHashids;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;
use Vinkla\Hashids\Facades\Hashids;

/**
 * @method Model|null findByHashid($hashid)
 * @method Model findByHashidOrFail($hashid)
 */
trait HasHashid 
{
	public static function bootHasHashid()
	{
		static::addGlobalScope(new HashidScope);
	}

	public function hashid()
	{
		return Hashids::connection($this->getHashidsConnection())
			->encode($this->getKey());
	}

	/**
	 * Decode the hashid to the id
	 *
	 * @param string $hashid
	 * @return int
	 *
	 * @throws InvalidArgumentException
	 */
	public function hashidToId($hashid)
	{
		$id = @Hashids::connection($this->getHashidsConnection())
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