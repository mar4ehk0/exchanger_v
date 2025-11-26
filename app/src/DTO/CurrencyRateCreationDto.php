<?php

namespace App\DTO;

use App\Exception\DtoExceptionInterface;

class CurrencyRateCreationDto implements DtoExceptionInterface
{
    public function __construct(
        #[Assert\NotBlank]
        public int $currency_id,
        #[Assert\NotBlank]
        public int $value,
        #[Assert\NotBlank]
        public string $date,
    ) {
    }

    public static function createFromArray(array $data): self
    {
        return new self($data['currency_id'], $data['value'], $data['date']);
    }
}
