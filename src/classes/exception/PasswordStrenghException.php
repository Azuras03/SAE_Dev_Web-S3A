<?php

namespace iutnc\deefy\exception;

use Exception;

/**
 * Exception thrown when a property name is incorrect
 * @author Hugo COLLIN
 */
class PasswordStrenghException extends Exception
{
    /**
     * Exception inherited constructor
     * @param string $msg
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(string $msg = "Veuillez saisir un mot de passe plus robuste.", int $code = 0, Exception $previous = null)
    {
        parent::__construct($msg, $code, $previous);
    }
}