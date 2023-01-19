<?php

namespace App\Services;

use App\Models\Zone;
use Carbon\Carbon;

class ParkingPriceService {

    public static function calculatePrice(int $zone_id, string $startTime, string $stopTime = null): int
    {
        $start = new Carbon($startTime);
        // $start = Carbon::now()->subDays(5);
        $stop = (!is_null($stopTime)) ? new Carbon($stopTime) : now();
        $totalTimeByMinutes = $stop->diffInMinutes($start);
        // dd($stopTime, $stop, $totalTimeByMinutes);

        $priceByMinutes = Zone::find($zone_id)->price_per_hour / 60;

        return ceil($totalTimeByMinutes * $priceByMinutes);
    }

}
