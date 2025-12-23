<?php

namespace App\Tests\Functional;

readonly class Endpoint
{
    public function __construct(
        public string $method,
        public string $path,
    ) {
    }
}
