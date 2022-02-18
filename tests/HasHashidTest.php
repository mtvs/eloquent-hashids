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
	public function it_can_encode_the_model_id_to_its_hashid()
	{
		$item = factory(Item::class)->create();

		$hashid = Hashids::encode($item->getKey());

		$this->assertEquals($hashid, $item->hashid());
		$this->assertEquals($hashid, $item->hashid);
	}

	/**
	 * @test
	 */
	public function it_can_append_its_hashid_to_its_serialized_version()
	{
		$item = factory(Item::class)->create();

		$hashid = Hashids::encode($item->getKey());

		$serialized = json_decode($item->append('hashid')->toJson());

		$this->assertEquals($hashid, $serialized->hashid);
	}

	/**
	 * @test
	 */
	public function it_can_enceode_an_arbitrary_id_to_its_hashid()
	{
		$item = new Item();

		$hashid = Hashids::encode(123);

		$this->assertEquals($hashid, $item->idToHashid(123));
	}	

	/**
	 * @test
	 */
	public function it_can_find_models_by_hashid()
	{
		$item = factory(Item::class)->create();
		
		$hashid = Hashids::encode($item->getKey());

		$found = Item::findByHashid($hashid);

		$this->assertNotNull($found);
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
		$item = factory(Item::class)->create();

		$hashid = Hashids::encode($item->getKey());

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
		$item = factory(Item::class)->create();

		$hashid = Hashids::encode($item->getKey());

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

    /**
     * @test
     */
    public function it_can_find_a_model_by_its_hashid_with_specific_columns()
    {
        $item = factory(Item::class)->create();

        $hashid = Hashids::encode($item->getKey());

        $selectedColumns = ['id'];

        $found = Item::findByHashid($hashid, $selectedColumns);

        $this->assertNotNull($found);
        $this->assertEquals($item->id, $found->id);
        $this->assertEquals($selectedColumns, array_keys($found->getAttributes()));
    }

    /**
     * @test
     */
    public function it_can_find_a_model_by_its_hashid_with_specific_columns_or_fail()
    {
        $item = factory(Item::class)->create();

        $hashid = Hashids::encode($item->getKey());

        $selectedColumns = ['id'];

        $found = Item::findByHashidOrFail($hashid, $selectedColumns);

        $this->assertEquals($item->id, $found->id);
        $this->assertEquals($selectedColumns, array_keys($found->getAttributes()));

        $item->delete();

        $this->expectException(ModelNotFoundException::class);

        Item::findByHashidOrFail($hashid);
    }
}
