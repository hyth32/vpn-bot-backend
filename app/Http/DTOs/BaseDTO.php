<?php

namespace App\Http\DTOs;

abstract class BaseDTO
{
    public function toArray()
    {
        return get_object_vars($this);
    }
}
