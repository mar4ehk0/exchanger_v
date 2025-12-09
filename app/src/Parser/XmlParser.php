<?php

namespace App\Parser;

use App\DTO\CBRFCurrencyRateDto;
use App\DTO\CurrencyCreationDto;
use App\DTO\ResultParsingRateDto;
use App\Interface\ParserInterface;
use Exception;
use Symfony\Component\Serializer\SerializerInterface;


class XmlParser implements ParserInterface
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {
    }

    /**
     * @return ResultParsingRateDto[]
     * @throws Exception
     */
    public function parse(CBRFCurrencyRateDto $dto): array
    {
        $ratesData = $this->serializer->decode($dto->data, 'xml');
        if (empty($ratesData) || empty($ratesData['Valute'])) {
            throw new Exception('empty rate values');
        }

        $result = [];
        foreach ($ratesData['Valute'] as $rate) {
            if (empty($rate['NumCode']) || empty($rate['CharCode']) || empty($rate['Value'])) {
                continue;
            }

            $result[] = new ResultParsingRateDto(
                charCode: $rate['CharCode'],
                numCode: $rate['NumCode'],
                value: (float) $rate['Value']
            );
        }

        return $result;
    }
}
