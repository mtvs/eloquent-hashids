<?php

namespace Mtvs\EloquentHashids;

trait HashidRouting
{
	/**
	 * @see parent
	 */
	public function resolveRouteBindingQuery($query, $value, $field = null)
	{
		if ($field && $field !== 'hashid') {
			return parent::resolveRouteBindingQuery($query, $value, $field);
		}

		return $query->byHashid($value);
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
