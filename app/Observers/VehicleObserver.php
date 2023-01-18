<?php

namespace App\Observers;

use App\Models\Vehicle;

class VehicleObserver
{
    /**
     * Handle the Vehicle "created" event.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return void
     */
    public function created(Vehicle $vehicle)
    {
        //
    }

    /**
     * Handle the Vehicle "updated" event.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return void
     */
    public function updated(Vehicle $vehicle)
    {
        //
    }

    /**
     * Handle the Vehicle "deleted" event.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return void
     */
    public function deleted(Vehicle $vehicle)
    {
        //
    }

    /**
     * Handle the Vehicle "restored" event.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return void
     */
    public function restored(Vehicle $vehicle)
    {
        //
    }

    /**
     * Handle the Vehicle "force deleted" event.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return void
     */
    public function forceDeleted(Vehicle $vehicle)
    {
        //
    }

    // using the vehicle observer - whenever we are 'creating' something on the Vehicle model
    // auto assign user_id the logged in user_id (similar to how multi-tenancy works...)
    /**
     * Handle the Vehicle "creating" event.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return void
     */
    public function creating(Vehicle $vehicle)
    {
        if (auth()->check()) {
            $vehicle->user_id = auth()->id();
        }
    }
}
