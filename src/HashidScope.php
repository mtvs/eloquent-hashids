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
		$builder->macro('findByHashid', function (Builder $builder, $hashid) {
			return $builder->byHashid($hashid)->first();
		});

		$builder->macro('findByHashidOrFail', function (Builder $builder, $hashid)
		{
			return $builder->byHashid($hashid)->firstOrFail();
		});

		$builder->macro('byHashid', function (Builder $builder, $hashid) {
			$model = $builder->getModel();

			return $builder->where($model->getKeyName(), $model->hashidToId($hashid));
		});
	}
}