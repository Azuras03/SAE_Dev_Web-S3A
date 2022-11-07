<?php

namespace netvod\exception;

use Exception;

class InvalidPropertyValueException extends Exception
{

    public function __construct(string $name, string $value)
    {
        parent::__construct("Invalid value for property: " . $name . " = " . $value);
    }

}