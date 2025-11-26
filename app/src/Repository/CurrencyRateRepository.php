<?php

namespace App\Repository;

use App\Entity\CurrencyRate;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CurrencyRateRepository
{
    private EntityRepository $repo;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repo = $this->em->getRepository(CurrencyRate::class);
    }

    public function add(CurrencyRate $currencyRate): void
    {
        $this->em->persist($currencyRate);
    }
}
