<?php

namespace Tests\Feature;

use App\Models\Item;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemControllerTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    public function testCreate()
    {
        $item = factory(Item::class)->make();
        $resp = $this->json('POST', '/api/items', $item->toArray());
        $resp->assertStatus(201);
        $this->assertDatabaseHas('items', $item->toArray());
    }
}
