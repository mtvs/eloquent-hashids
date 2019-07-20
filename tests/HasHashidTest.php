<?php

namespace Mtvs\EloquentHashids\Tests;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mtvs\EloquentHashids\Tests\Models\Item;
use Vinkla\Hashids\Facades\Hashids;

class HasHashidTest extends TestCase 
{
	use RefreshDatabase;

	/**
	 * @test
	 */
	public function it_can_generate_the_hashid()
	{
		$item = Item::create();
		$hashId = $item->hashid();
		$decoded = Hashids::connection(
			$item->getHashidsConnection()
		)->decode($hashId)[0];

		$this->assertEquals(
			$item->getKey(),
			$decoded
		);
	}

	/**
	 * @test
	 */
	public function it_can_find_models_by_hashid()
	{
		$item = Item::create();
		$hashid = Hashids::connection($item->getHashidsConnection())->encode(
			$item->getKey()
		);

		$found = Item::findByHashid($hashid);

		$this->assertEquals($item->id, $found->id);
	}

	/**
	 * @test
	 */
	public function it_can_find_a_model_by_its_hashid_or_fail()
	{
		$item = Item::create();
		$hashid = Hashids::connection($item->getHashidsConnection())->encode(
			$item->getKey()
		);

		$found = Item::findByHashidOrFail($hashid);

		$this->assertEquals($item->id, $found->id);

		$item->delete();

		$this->expectException(ModelNotFoundException::class);

		Item::findByHashidOrFail($hashid);
	}
}
