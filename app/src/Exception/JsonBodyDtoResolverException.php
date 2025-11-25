<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class JsonBodyDtoResolverException extends \Exception
{
    public function __construct(\Throwable $previous)
    {
        $msg = 'Bad Request';
        parent::__construct($msg, Response::HTTP_BAD_REQUEST, $previous);
    }
}
