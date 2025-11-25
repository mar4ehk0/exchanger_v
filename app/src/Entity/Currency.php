<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping as ORM;

#[Entity()]
#[Table(name: 'currency')]
class Currency
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $numCode;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $charCode;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    public function __construct(
        string $numCode,
        string $charCode,
        string $name,
    )
    {
        $this->numCode = $numCode;
        $this->charCode = $charCode;
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNumCode(): string
    {
        return $this->numCode;
    }

    public function getCharCode(): string
    {
        return $this->charCode;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setNumCode(string $numCode): void
    {
        $this->numCode = $numCode;
    }

    public function setCharCode(string $charCode): void
    {
        $this->charCode = $charCode;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
