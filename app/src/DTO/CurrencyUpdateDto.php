<?php

namespace App\DTO;

use App\Exception\CurrencyUpdateDtoException;
use App\Exception\DtoExceptionInterface;
use App\Interface\JsonBodyDtoRequestInterface;
use Symfony\Component\Validator\Constraints as Assert;

readonly class CurrencyUpdateDto implements DtoExceptionInterface, JsonBodyDtoRequestInterface
{
    public function __construct(
        #[Assert\NotBlank]
        public int $id,
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
        if (!array_key_exists('id', $data)) {
            throw CurrencyUpdateDtoException::missingNumCode();
        }
        if (!array_key_exists('numCode', $data)) {
            throw CurrencyUpdateDtoException::missingNumCode();
        }
        if (!array_key_exists('charCode', $data)) {
            throw CurrencyUpdateDtoException::missingCharCode();
        }
        if (!array_key_exists('name', $data)) {
            throw CurrencyUpdateDtoException::missingName();
        }

        return new self($data['id'], $data['numCode'], $data['charCode'], $data['name']);
    }
}
