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

		$hashid = Hashids::encode($item->getKey());

		$this->assertEquals($hashid, $item->hashid());
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
	public function it_returns_null_when_cannot_find_a_model_with_a_hashid()
	{
		$hashid = Hashids::encode(1);

		$found = Item::findByHashid($hashid);

		$this->assertNull($found);
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

	/**
	 * @test
	 */
	public function it_can_decode_a_hashid_to_the_id()
	{
		$item = Item::create();
		$hashid = Hashids::connection($item->getHashidsConnection())->encode(
			$item->getKey()
		);

		$id = (new Item)->hashidToId($hashid);

		$this->assertEquals($item->id, $id);
	}

	/**
	 * @test
	 */
	public function it_can_handle_invalid_hashids_properly()
	{
		$this->assertNull((new Item)->hashidToId('not a hashid'));

		$this->assertNull(Item::findByHashid('not a hashid'));

		$this->expectException(ModelNotFoundException::class);
		Item::findByHashidOrFail('not a hashid');
	}
}
