<?php

namespace Tests\Feature;

use App\Models\Item;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemControllerTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    public function testIndex()
    {
        $items = factory(Item::class, 50)->create();
        $response = $this->get(route('items.index'));
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'total',
                'per_page',
                'current_page'
            ]);
    }

    public function testCreate()
    {
        $item = factory(Item::class)->make();
        $resp = $this->json('POST', '/api/items', $item->toArray());
        $resp->assertStatus(201);
        $this->assertDatabaseHas('items', $item->toArray());
    }

    public function testShow()
    {
        $items = factory(Item::class, 50)->create();

        foreach ($items as $item) {
            $response = $this->get(route('items.show', compact('item')));
            $response->assertStatus(200)
                ->assertJsonStructure([
                    'id',
                    'title',
                    'description',
                    'active',
                    'created_at',
                    'updated_at'
                ]);
        }
        $response = $this->get(route('items.show', ['item' => 0]));
        $response->assertStatus(404);
    }

    public function testUpdate()
    {
        $items = factory(Item::class, 5)->create();
        $item = Item::all()->random(1)->first();

        $newItemData = (factory(Item::class)->make())->toArray();
        $response = $this->json('PUT', route('items.update', compact('item')), $newItemData);
        $response->assertStatus(204);
        $item = Item::find($item->id);
        $keys = array_keys(array_diff_assoc($item->toArray(), $newItemData));
        $this->assertEquals($keys, ['id', 'created_at', 'updated_at']);

    }

    public function testDelete()
    {
        $items = factory(Item::class, 5)->create();
        foreach ($items as $item) {
            $resp = $this->get(route('items.update', compact('item')));
            $resp->assertStatus(200);
            $resDelete = $this->json('DELETE', route('items.update', compact('item')));
            $resDelete->assertStatus(204);
            $resp = $this->get(route('items.update', compact('item')));
            $resp->assertStatus(404);
            $this->assertTrue(Item::find($item->id) == null);

        }
    }

}
