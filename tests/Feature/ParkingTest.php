<?php

use App\Models\Parking;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Zone;
use Tests\TestCase;

class ParkingTest extends TestCase
{
    private object $user;

    private object $vehicle;

    private object $zone;

    private string $reg_number;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->reg_number = 'GK123456';
        $this->vehicle = Vehicle::factory()->create(
            [
                'user_id' => $this->user->id,
                'reg_number' => $this->reg_number,
            ]);
        $this->zone = Zone::first();
    }

    public function testUserCanStartParking(): void
    {
        $response = $this->actingAs($this->user)->postJson('/api/v1/parkings/start', [
            'vehicle_id' => $this->vehicle->id,
            'zone_id' => $this->zone->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data'])
            ->assertJson([
                'data' => [
                    'start_time' => now()->toDateTimeString(),
                    'stop_time' => null,
                    'total_price' => 0,
                ],
            ]);

        $this->assertDatabaseCount('parkings', '1');
        $this->assertDatabaseHas('parkings', [
            'vehicle_id' => $this->vehicle->id,
            'zone_id' => $this->zone->id,
        ]);
    }

    public function testUserCanGetOngoingParkingWithCorrectPrice(): void
    {
        $this->actingAs($this->user)->postJson('/api/v1/parkings/start', [
            'vehicle_id' => $this->vehicle->id,
            'zone_id' => $this->zone->id,
        ]);

        $this->travel(2)->hours();

        $parking = Parking::first();
        $response = $this->actingAs($this->user)->getJson('/api/v1/parkings/'.$parking->id);

        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJson([
                'data' => [
                    'stop_time' => null,
                    'total_price' => $this->zone->price_per_hour * 2,
                ],
            ]);

        $this->assertDatabaseCount('parkings', '1');
        $this->assertDatabaseHas('parkings', [
            'vehicle_id' => $this->vehicle->id,
            'zone_id' => $this->zone->id,
        ]);
    }

    public function testUserCanStopParking()
    {
        $user = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);
        $zone = Zone::first();

        $this->actingAs($user)->postJson('/api/v1/parkings/start', [
            'vehicle_id' => $vehicle->id,
            'zone_id' => $zone->id,
        ]);

        $this->travel(2)->hours();

        $parking = Parking::first();
        $response = $this->actingAs($user)->putJson('/api/v1/parkings/'.$parking->id);

        $updatedParking = Parking::find($parking->id);

        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJson([
                'data' => [
                    'start_time' => $updatedParking->start_time->toDateTimeString(),
                    'stop_time' => $updatedParking->stop_time->toDateTimeString(),
                    'total_price' => $updatedParking->total_price,
                ],
            ]);

        $this->assertDatabaseCount('parkings', '1');
    }

    public function testUserPassingEmptyParametersCanSeeCorrectValidationMessages(): void
    {
        $response = $this->actingAs($this->user)->postJson('/api/v1/parkings/start', [
            'vehicle_id' => '',
            'zone_id' => '',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The vehicle id field is required. (and 1 more error)',
                'errors' => [
                    'vehicle_id' => [
                        'The vehicle id field is required.',
                    ],
                    'zone_id' => [
                        'The zone id field is required.',
                    ],
                ],
            ]);
    }

    public function testUserPassingEmptyVehicleIdParameterCanSeeCorrectValidationMessages(): void
    {
        $response = $this->actingAs($this->user)->postJson('/api/v1/parkings/start', [
            'vehicle_id' => '',
            'zone_id' => $this->zone->id,
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The vehicle id field is required.',
                'errors' => [
                    'vehicle_id' => [
                        'The vehicle id field is required.',
                    ],
                ],
            ]);
    }

    public function testUserPassingEmptyZoneIdParameterCanSeeCorrectValidationMessages(): void
    {
        $response = $this->actingAs($this->user)->postJson('/api/v1/parkings/start', [
            'vehicle_id' => $this->vehicle->id,
            'zone_id' => '',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The zone id field is required.',
                'errors' => [
                    'zone_id' => [
                        'The zone id field is required.',
                    ],
                ],
            ]);
    }

    public function testUserPassingIncorrectParameterTypesCanSeeCorrectValidationMessages(): void
    {
        $response = $this->actingAs($this->user)->postJson('/api/v1/parkings/start', [
            'vehicle_id' => 'INCORRECT TYPE',
            'zone_id' => 'INCORRECT TYPE',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The vehicle id must be an integer. (and 1 more error)',
                'errors' => [
                    'vehicle_id' => [
                        'The vehicle id must be an integer.',
                    ],
                    'zone_id' => [
                        'The zone id must be an integer.',
                    ],
                ],
            ]);
    }

    public function testUserPassingIncorrectParameterVehicleTypeCanSeeCorrectValidationMessages(): void
    {
        $response = $this->actingAs($this->user)->postJson('/api/v1/parkings/start', [
            'vehicle_id' => 'INCORRECT TYPE',
            'zone_id' => $this->zone->id,
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The vehicle id must be an integer.',
                'errors' => [
                    'vehicle_id' => [
                        'The vehicle id must be an integer.',
                    ],
                ],
            ]);
    }

    public function testUserPassingIncorrectParameterZoneTypeCanSeeCorrectValidationMessages(): void
    {
        $response = $this->actingAs($this->user)->postJson('/api/v1/parkings/start', [
            'vehicle_id' => $this->vehicle->id,
            'zone_id' => 'INCORRECT TYPE',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The zone id must be an integer.',
                'errors' => [
                    'zone_id' => [
                        'The zone id must be an integer.',
                    ],
                ],
            ]);
    }
}
