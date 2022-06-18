<?php

namespace Mtvs\EloquentHashids;

use Illuminate\Support\Str;

trait HashidRouting
{
	/**
	 * @see parent
	 */
	public function resolveRouteBindingQuery($query, $value, $field = null)
	{
		$field = $field ?? $this->getRouteKeyName();

		if (
			$field && $field !== 'hashid' &&
			// Check for qualified columns
			Str::afterLast($field, '.') !== 'hashid' && 
			// Avoid risking breaking backward compatibility by modifying 
			// the getRouteKeyName() to return 'hashid' instead of null
			Str::afterLast($field, '.') !== ''
		) {
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
