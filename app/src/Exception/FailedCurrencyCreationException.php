<?php

namespace App\Exception;

class FailedCurrencyCreationException extends BaseServiceException
{
    private function __construct(
        string $systemMessage,
        string $humanMessage,
    ) {
        parent::__construct($systemMessage, $humanMessage);
    }

    public static function numCodeNotUnique(string $numCode): self
    {
        $sysMsg = sprintf('Validation failed: NumCode "%s" is already in use', $numCode);
        $humanMsg = sprintf('The field "NumCode" must be unique, value "%s" is already taken', $numCode);
        return new self($sysMsg, $humanMsg);
    }

    public static function charCodeNotUnique(string $charCode): self
    {
        $sysMsg = sprintf('Validation failed: CharCode "%s" is already in use', $charCode);
        $humanMsg = sprintf('The field "CharCode" must be unique, value "%s" is already taken', $charCode);
        return new self($sysMsg, $humanMsg);
    }
}
