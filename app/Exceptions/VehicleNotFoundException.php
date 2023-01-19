<?php

namespace App\Exceptions;

use Exception;

class VehicleNotFoundException extends Exception
{
    public function render($request)
    {
        return response()->json(["error" => true, "message" => $this->getMessage()]);
    }
}
