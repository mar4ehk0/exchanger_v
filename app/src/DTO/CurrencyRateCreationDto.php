<?php

namespace App\DTO;

use App\Exception\DtoExceptionInterface;
use App\Interface\JsonBodyDtoRequestInterface;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

class CurrencyRateCreationDto implements DtoExceptionInterface, JsonBodyDtoRequestInterface
{
    public function __construct(
        #[Assert\NotBlank]
        public int $currencyId,
        #[Assert\NotBlank]
        public int $value,
        #[Assert\NotBlank]
        public DateTimeImmutable $date,
    ) {
    }

//    public static function createFromArray(array $data): self
//    {
//        return new self($data['currencyId'], $data['value'], $data['date']);
//    }
}
