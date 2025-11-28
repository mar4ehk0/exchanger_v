<?php

namespace App\View;

use App\Entity\Currency;

class CurrencyView
{
    public function __construct(private Currency $currency)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->currency->getId(),
            'num_code' => $this->currency->getNumCode(),
            'char_code' => $this->currency->getCharCode(),
            'name' => $this->currency->getName(),
        ];
    }
}
