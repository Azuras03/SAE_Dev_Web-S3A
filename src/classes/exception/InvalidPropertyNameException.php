<?php

namespace netvod\exception;

use Exception;

class InvalidPropertyNameException extends Exception
{
    public function __construct(string $name)
    {
        parent::__construct("Invalid Property: " . $name);
    }

}