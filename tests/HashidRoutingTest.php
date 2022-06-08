<?php

namespace Mtvs\EloquentHashids\Tests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Mtvs\EloquentHashids\Tests\Models\Child;
use Mtvs\EloquentHashids\Tests\Models\Item;
use Vinkla\Hashids\Facades\Hashids;

class HashidRoutingTest extends TestCase
{
	/**
	 * @test
	 */
	public function it_can_resolve_a_hashid_in_a_linked_route_binding()
	{
		$given = factory(Item::class)->create();

		$hashid = Hashids::encode($given->getKey());

		Route::get('/item/{item}', function (Item $item) use ($given) {
			$this->assertEquals($given->id, $item->id);
		})->middleware(SubstituteBindings::class);

		$this->get("/item/$hashid");
	}

	/**
	 * @test
	 */
	public function it_supports_specifying_a_field_in_the_route_binding()
	{
		$given = factory(Item::class)->create(['slug' => 'item-1']);

		Route::get('/item/{item:slug}', function (Item $item) use ($given) {
			$this->assertEquals($given->id, $item->id);
		})->middleware(SubstituteBindings::class);

		$this->get("/item/item-1");
	}

	/**
	 * @test
	 */
	public function it_returns_the_hashid_of_a_model_as_its_route_key()
	{
		$item = factory(Item::class)->create();

		$hashid = Hashids::encode($item->id);

		$this->assertEquals($hashid, $item->getRouteKey());
	}

	/**
	 * @test
	 */
	public function it_can_resolve_hashid_of_child_routing_binding(){
		$givenChild = factory(Child::class)->create();
		$givenItem = $givenChild->item;

		Route::get('/items/{item}/children/{child}', function (Item $item, Child $child) use ($givenChild, $givenItem) {
			$this->assertEquals($givenItem->id, $item->id);
			$this->assertEquals($givenChild->id, $child->id);
		})->middleware(SubstituteBindings::class)->scopeBindings();

		$this->get("/items/{$givenItem->getRouteKey()}/children/{$givenChild->getRouteKey()}")->assertOk();
	}

	/**
	 * @test
	 */
	public function it_can_resolve_specifying_field_of_child_routing_binding(){
		$givenChild = factory(Child::class)->create();
		$givenItem = $givenChild->item;

		Route::get('/items/{item}/children/{child:id}', function (Item $item, Child $child) use ($givenChild, $givenItem) {
			$this->assertEquals($givenItem->id, $item->id);
			$this->assertEquals($givenChild->id, $child->id);
		})->middleware(SubstituteBindings::class)->scopeBindings();

		$this->get("/items/{$givenItem->getRouteKey()}/children/{$givenChild->id}")->assertOk();
	}
}
