<?php

namespace App\Exception;

use Exception;

abstract class BaseServiceException extends Exception
{
    public function __construct(
        public readonly string $systemMessage,
        public readonly string $humanMessage,
    ) {
        parent::__construct($systemMessage);
    }
}
