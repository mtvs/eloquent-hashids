<?php

namespace Mtvs\EloquentHashids;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class HashidScope implements Scope
{
	public function apply(Builder $builder, Model $model)
	{
		
	}

	public function extend(Builder $builder)
	{
		$builder->macro('findByHashid', function (Builder $builder, $hashid, $columns = ['*']) {
			$id = $builder->getModel()->hashidToId($hashid);

			return $builder->find($id, $columns);
		});

		$builder->macro('findByHashidOrFail', function (Builder $builder, $hashid, $columns = ['*'])
		{
			$id = $builder->getModel()->hashidToId($hashid);

			return $builder->findOrFail($id, $columns);
		});
	}
}