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
		return $this->idToHashid($this->getKey());
	}

	/**
	 * Decode the hashid to the id
	 *
	 * @param string $hashid
	 * @return int|null
	 */
	public function hashidToId($hashid)
	{
		return @Hashids::connection($this->getHashidsConnection())
			->decode($hashid)[0];
	}

	/**
	 * Encode an id to its equivalent hashid
	 *
	 * @param string $id
	 * @return string|null
	 */
	public function idToHashid($id)
	{
		return @Hashids::connection($this->getHashidsConnection())
			->encode($id);
	}

	public function getHashidsConnection()
	{
		return config('hashids.default');
	}

	protected function getHashidAttribute()
    {
        return $this->hashid();
    }
}
