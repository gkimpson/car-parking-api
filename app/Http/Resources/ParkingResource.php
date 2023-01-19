<?php

namespace App\Http\Resources;

use App\Services\ParkingPriceService;
use Illuminate\Http\Resources\Json\JsonResource;

class ParkingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $totalPrice = $this->total_price ?? ParkingPriceService::calculatePrice(
            $this->zone_id,
            $this->start_time,
            $this->stop_time
        );

        $startDate = $this->start_time ?? null;
        $stopDate = $this->stop_time ?? null;
        $parkingDurationInSecs = ($stopDate) ? $startDate->diffInSeconds($stopDate) : 0;

        return [
            'id' => $this->id,
            'zone' => [
                'name' => $this->zone->name,
                'price_per_hour' => $this->zone->price_per_hour,
            ],
            'vehicle' => [
                'reg_number' => $this->vehicle->reg_number,
            ],
            'start_time' => $this->start_time,
            'stop_time' => $this->stop_time,
            'total_price' => $totalPrice,
            'parking_duration_seconds' => $parkingDurationInSecs,
        ];
    }
}
