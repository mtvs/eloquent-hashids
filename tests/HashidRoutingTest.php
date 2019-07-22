<?php

namespace Mtvs\EloquentHashids\Tests;

use Mtvs\EloquentHashids\Tests\Models\Item;
use Vinkla\Hashids\Facades\Hashids;

class HashidRoutingTest extends TestCase
{
	/**
	 * @test
	 */
	public function it_resolves_the_hashid_as_the_route_binding()
	{
		$item = Item::create();
		$hashid = Hashids::connection($item->getHashidsConnection())
			->encode($item->getKey());

		$resolved = (new Item)->resolveRouteBinding($hashid);

		$this->assertNotNull($resolved);
		$this->assertEquals($item->id, $resolved->id);
	}

	/**
	 * @test
	 */
	public function it_returns_the_hashid_as_the_route_keys()
	{
		$item = Item::create();
		$hashid = Hashids::connection($item->getHashidsConnection())
			->encode($item->id);

		$routeKey = $item->getRouteKey();
		
		$this->assertEquals($hashid, $routeKey);
	}
}