<?php

namespace App\DTO;

readonly class ResultParsingRateDto
{
    public function __construct(
        public string $charCode,
        public string $numCode,
        public float $value,
    ) {
    }
}
