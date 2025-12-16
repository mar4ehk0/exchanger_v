<?php

namespace App\Exception;

class CurrencyUpdateDtoException extends BaseServiceException implements DtoExceptionInterface
{
    public static function missingId(): self
    {
        $sysMsg = 'Validation failed: property is missing "Id"';
        $humanMsg = 'Currency should be have Id';

        return new self($sysMsg, $humanMsg);
    }

    public static function missingNumCode(): self
    {
        $sysMsg = 'Validation failed: property is missing "numCode"';
        $humanMsg = 'Currency should be have number code';

        return new self($sysMsg, $humanMsg);
    }

    public static function missingCharCode(): self
    {
        $sysMsg = 'Validation failed: property is missing "charCode"';
        $humanMsg = 'Currency should be have number code';

        return new self($sysMsg, $humanMsg);
    }

    public static function missingName(): self
    {
        $sysMsg = 'Validation failed: property is missing "name"';
        $humanMsg = 'Currency should be have name';

        return new self($sysMsg, $humanMsg);
    }
}
