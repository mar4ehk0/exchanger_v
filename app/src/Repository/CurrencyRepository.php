<?php

namespace App\Repository;

use App\Entity\Currency;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CurrencyRepository
{
    private EntityRepository $repo;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repo = $this->em->getRepository(Currency::class);
    }

    public function findByNumCode(string $numCode): ?Currency
    {
        $currency = $this->repo->findBy(['numCode' => $numCode]);
        if ($currency instanceof Currency) {
            return $currency;
        }

        return null;
    }

    public function findByCharCode(string $charCode): ?Currency
    {
        $currency = $this->repo->findBy(['charCode' => $charCode]);
        if ($currency instanceof Currency) {
            return $currency;
        }

        return null;

    }

    public function add(Currency $currency): void
    {
        $this->em->persist($currency);
    }

}
