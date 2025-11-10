<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping as ORM;

#[Entity()]
#[Table(name: 'currency_rate')]
class CurrencyRate
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Currency::class)]
    #[ORM\JoinColumn(name: 'currency_id', referencedColumnName: 'id', nullable: false)]
    private Currency $currency;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 4)]
    private string $value;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private DateTimeInterface $date;

    public function __construct(
        Currency $currency,
        string $value,
        DateTimeInterface $date,
    )
    {
        $this->currency = $currency;
        $this->value = $value;
        $this->date = $date;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }
}
