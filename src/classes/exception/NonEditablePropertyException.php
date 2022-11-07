<?php

namespace netvod\exception;

use Exception;

class NonEditablePropertyException extends Exception
{
    public function __construct(string $name)
    {
        parent::__construct("Non-editable Property: " . $name);
    }

}