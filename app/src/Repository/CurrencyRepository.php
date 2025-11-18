<?php

namespace App\Repository;

use App\Entity\Currency;
use App\Exception\NotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Exception;

class CurrencyRepository
{
    private EntityRepository $repo;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repo = $this->em->getRepository(Currency::class);
    }

    public function findById(int $id): ?Currency
    {
        $currency = $this->repo->findOneBy(['id' => $id]);
        if ($currency instanceof Currency) {
            return $currency;
        }

        return null;
    }

    public function getById(int $id): Currency
    {
        $currency = $this->repo->find($id);
        if ($currency instanceof Currency) {
            return $currency;
        }

        throw new NotFoundException(Currency::class, $id);
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
