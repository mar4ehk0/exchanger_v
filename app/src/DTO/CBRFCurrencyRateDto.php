<?php

namespace App\DTO;

readonly class CBRFCurrencyRateDto
{
    public function __construct(
        public string $contentType,
        public string $data
    ) {
    }
}
