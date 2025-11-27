<?php

namespace App\DTO;

use App\Exception\DtoExceptionInterface;
use App\Interface\JsonBodyDtoRequestInterface;
use Symfony\Component\Validator\Constraints as Assert;

// class CurrencyRateCreationDto implements DtoExceptionInterface
class CurrencyRateCreationDto implements JsonBodyDtoRequestInterface
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
