<?php

namespace Mtvs\EloquentHashids;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HashidRouting
{
	/**
	 * @see parent
	 */
	public function resolveRouteBinding($value, $field = null)
	{
		if ($field) {
			return parent::resolveRouteBinding($value, $field);
		}

		return $this->findByHashid($value);
	}

	/**
    * @see parent
    */
    public function resolveChildRouteBinding($childType, $value, $field)
    {
        $relationship = $this->{Str::plural(Str::camel($childType))}();

		if(!($relationship instanceof Model)){
			$relationship = $relationship->getRelated();
		}

        if (null === $field && \in_array(HashidRouting::class, class_uses_recursive($relationship), true)) {
			$value = $relationship->hashidToId($value);
			$field = $relationship->getKeyName();
        }

        return parent::resolveChildRouteBinding($childType, $value, $field);
    }

	/**
	 * @see parent
	 */
	public function getRouteKey()
	{
		return $this->hashid();
	}

	/**
	 * @see parent
	 */
	public function getRouteKeyName()
	{
		return null;
	}
}
