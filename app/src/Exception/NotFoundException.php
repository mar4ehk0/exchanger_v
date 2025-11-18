<?php

namespace App\Exception;

use Exception;

class NotFoundException extends Exception
{
    public function __construct(string $className, int $id)
    {
        $msg = sprintf('Not found: %s, id: %d', $className, $id);

        parent::__construct($msg);
    }
}
