<?php

namespace App\View;

use App\Entity\CurrencyRate;

class CurrencyRateView
{
    public function __construct(private CurrencyRate $currencyRate)
    {
    }

    public function toArray(): array
    {
        $currency = $this->currencyRate->getCurrency();

        return [
            'id' => $this->currencyRate->getId(),
            'currency_id' => $currency->getId(),
            'value' => $this->currencyRate->getValue(),
            'date' => $this->currencyRate->getDate(),
        ];
    }
}
