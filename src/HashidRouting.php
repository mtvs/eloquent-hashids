<?php

namespace Mtvs\EloquentHashids;

trait HashidRouting
{
	/**
	 * @see parent
	 */
	public function resolveRouteBinding($value)
	{
		return $this->findByHashid($value);
	}
}