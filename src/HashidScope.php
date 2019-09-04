<?php

namespace Mtvs\EloquentHashids;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Scope;

class HashidScope implements Scope
{
	public function apply(Builder $builder, Model $model)
	{
		
	}

	public function extend(Builder $builder)
	{
		$builder->macro('findByHashid', function (Builder $builder, $hashid) {
			try {
				$id = $builder->getModel()->hashidToId($hashid);
			} catch (Exception $exception) {
				return null;
			}

			return $builder->find($id);
		});

		$builder->macro('findByHashidOrFail', function (Builder $builder, $hashid)
		{
			try {
				$id = $builder->getModel()->hashidToId($hashid);
			} catch (Exception $exception) {
				throw (new ModelNotFoundException)->setModel(
					get_class($this->model),
					$id
				);
			}

			return $builder->findOrFail($id);
		});
	}
}