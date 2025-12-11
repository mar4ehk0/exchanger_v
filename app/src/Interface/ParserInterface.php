<?php

namespace App\Interface;

use App\DTO\CBRFCurrencyRateDto;
use App\DTO\ResultParsingRateDto;
use Exception;

interface ParserInterface
{
    /**
     * @return ResultParsingRateDto[]
     * @throws Exception
     */
    public function parse(CBRFCurrencyRateDto $dto): array;
}
