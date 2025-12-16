<?php

namespace App\Parser;

use App\DTO\CBRFCurrencyRateDto;
use App\Interface\ParserInterface;

use function json_decode;

class JsonParser implements ParserInterface
{
    public function parse(CBRFCurrencyRateDto $dto): array
    {
        //        json_decode($dto->data)
        // TODO: Implement parse() method.
    }
}
