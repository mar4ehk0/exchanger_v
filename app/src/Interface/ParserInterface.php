<?php

namespace App\Interface;

use App\DTO\CBRFCurrencyRateDto;

interface ParserInterface
{
    public function parse(CBRFCurrencyRateDto $dto): array;
}
