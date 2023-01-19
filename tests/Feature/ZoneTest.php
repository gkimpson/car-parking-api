<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ZoneTest extends TestCase
{
    use RefreshDatabase;

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
}
