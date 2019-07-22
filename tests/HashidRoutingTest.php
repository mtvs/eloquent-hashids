<?php

namespace Mtvs\EloquentHashids\Tests;

use Mtvs\EloquentHashids\Tests\Models\Item;
use Vinkla\Hashids\Facades\Hashids;

class HashidRoutingTest extends TestCase
{
	/**
	 * @test
	 */
	public function it_resolves_hashids_as_route_bindings()
	{
		$item = Item::create();
		$hashid = Hashids::connection($item->getHashidsConnection())
			->encode($item->getKey());

		$resolved = (new Item)->resolveRouteBinding($hashid);

		$this->assertNotNull($resolved);
		$this->assertEquals($item->id, $resolved->id);
	}
}