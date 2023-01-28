<?php

namespace Tests\Feature;

use Tests\TestCase;

class ZoneTest extends TestCase
{
    public function testPublicUserCanGetAllZones()
    {
        $response = $this->getJson('/api/v1/zones');

        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure(['data' => [
                ['*' => 'id', 'name', 'price_per_hour'],
            ]])
            ->assertJsonPath('data.0.id', 1)
            ->assertJsonPath('data.0.name', 'Green Zone')
            ->assertJsonPath('data.0.price_per_hour', 100)
            ->assertJsonPath('data.1.id', 2)
            ->assertJsonPath('data.1.name', 'Yellow Zone')
            ->assertJsonPath('data.1.price_per_hour', 200)
            ->assertJsonPath('data.2.id', 3)
            ->assertJsonPath('data.2.name', 'Red Zone')
            ->assertJsonPath('data.2.price_per_hour', 300);
    }

    public function testPublicUserCanGetSingleZone()
    {
        $response = $this->getJson('/api/v1/zones/1');

        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(1)
            ->assertJsonPath('data.id', 1)
            ->assertJsonPath('data.name', 'Green Zone')
            ->assertJsonPath('data.price_per_hour', 100);
    }
}
