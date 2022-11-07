<?php

namespace iutnc\deefy\exception;

use Exception;

/**
 * Exception thrown when a user is already in the database
 * @author Hugo COLLIN
 */
class AlreadyStoredException extends Exception
{
    /**
     * Exception inherited constructor
     * @param string $msg
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(string $msg = "Information déjà dans la base.", int $code = 0, Exception $previous = null)
    {
        parent::__construct($msg, $code, $previous);
    }
}