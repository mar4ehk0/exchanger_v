<?php

namespace App\DTO;

use App\Exception\CurrencyCreationDtoException;
use App\Exception\DtoExceptionInterface;
use Symfony\Component\Validator\Constraints as Assert;

readonly class CurrencyCreationDto implements DtoExceptionInterface
{
    public function __construct(
        #[Assert\NotBlank]
        public string $numCode,
        #[Assert\NotBlank]
        public string $charCode,
        #[Assert\NotBlank]
        public string $name,
    ) {
    }

    public static function createFromArray(array $data): self
    {
        if (!array_key_exists('numCode', $data)) {
            throw CurrencyCreationDtoException::missingNumCode();
        }
        if (!array_key_exists('charCode', $data)) {
            throw CurrencyCreationDtoException::missingCharCode();
        }
        if (!array_key_exists('name', $data)) {
            throw CurrencyCreationDtoException::missingName();
        }

        return new self($data['numCode'], $data['charCode'], $data['name']);
    }
}
