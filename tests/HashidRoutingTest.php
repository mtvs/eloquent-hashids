<?php

namespace Mtvs\EloquentHashids\Tests;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Mtvs\EloquentHashids\Tests\Models\Item;
use Mtvs\EloquentHashids\Tests\Models\ItemWithCustomRouteKeyName;
use Mtvs\EloquentHashids\Tests\Models\Comment;
use Mtvs\EloquentHashids\Tests\Models\Vendor;
use Vinkla\Hashids\Facades\Hashids;

class HashidRoutingTest extends TestCase
{
	/**
	 * @test
	 */
	public function it_can_resolve_a_route_binding()
	{
		$given = factory(Item::class)->create();

		$hashid = Hashids::encode($given->getKey());

		Route::get('/item/{item}', function (Item $item) use ($given) {
			$this->assertEquals($given->id, $item->id);
		})->middleware(SubstituteBindings::class);

		$this->get("/item/$hashid");
	}

	/** @test */
	public function it_supports_custom_route_key_names()
	{
		$given = ItemWithCustomRouteKeyName::create(
			factory(Item::class)->raw()
		);

		Route::get('/item/{item}', function (ItemWithCustomRouteKeyName $item) use ($given) {
			$this->assertEquals($given->id, $item->id);
		})->middleware(SubstituteBindings::class);

		$this->get("/item/$given->slug");

		Route::get('/admin/item/{item:hashid}', function (ItemWithCustomRouteKeyName $item) use ($given) {
			$this->assertEquals($given->id, $item->id);
		})->middleware(SubstituteBindings::class);

		$this->get("/admin/item/$given->hashid");

	}

	/** @test */
	public function it_supports_resolving_softdeletable_route_bindings()
	{
		$given = factory(Item::class)->state('softDeleted')->create();

		$hashid = Hashids::encode($given->getKey());

		Route::get('/item/{item}', function (Item $item) use ($given) {
			$this->assertEquals($given->id, $item->id);
		})->withTrashed()->middleware(SubstituteBindings::class);

		$this->get("/item/$hashid");
	}

	/** @test */
	public function it_supports_resolving_child_route_bindings()
	{
		$item = factory(Item::class)->create();

		$given = $item->comments()->save(
			factory(Comment::class)->make()
		);

		Route::get(
			'/item/{item}/comments/{comment}', 
			function (Item $item, Comment $comment) use ($given) {
				$this->assertEquals($given->id, $comment->id);
			})->scopeBindings()->middleware(SubstituteBindings::class);

		$this->get("item/{$item->hashid}/comments/{$given->hashid}");

		$notRelated = factory(Comment::class)->create();

		$this->expectException(ModelNotFoundException::class);

		$this->get("item/{$item->hashid}/comments/{$notRelated->hashid}");
	}

	/** @test */
	public function it_supports_resolving_special_child_route_bindings()
	{
		$vendor = factory(Vendor::class)->create();

		$item = $vendor->items()->save(
			factory(Item::class)->make()
		);

		$given = $item->comments()->save(
			factory(Comment::class)->make()
		);

		Route::get(
			'/vendor/{vendor}/comments/{comment}', 
			function (Vendor $vendor, Comment $comment) use ($given) {
				$this->assertEquals($given->id, $comment->id);
			})->scopeBindings()->middleware(SubstituteBindings::class);		

		$this->get("/vendor/{$vendor->id}/comments/{$given->hashid}");
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

	/** @test */
	public function it_supports_specifying_the_hashid_in_the_route_binding()
	{
		$given = factory(Item::class)->create();

		$hashid = Hashids::encode($given->getKey());

		Route::get('/item/{item:hashid}', function (Item $item) use ($given) {
			$this->assertEquals($given->id, $item->id);
		})->middleware(SubstituteBindings::class);

		$this->get("/item/$hashid");
	}

	/**
	 * @test
	 */
	public function it_returns_the_hashid_of_the_model_as_its_route_key()
	{
		$item = factory(Item::class)->create();

		$hashid = Hashids::encode($item->id);

		$this->assertEquals($hashid, $item->getRouteKey());
	}
}
