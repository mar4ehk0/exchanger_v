<?php

namespace App\Service;

use App\DTO\CurrencyRateCreationDto;
use App\Entity\Currency;
use App\Entity\CurrencyRate;
use App\Exception\NotFoundException;
use App\Repository\CurrencyRepository;
use App\Repository\CurrencyRateRepository;
use Doctrine\ORM\EntityManagerInterface;

class CurrencyRateService
{
    public function __construct(
        private EntityManagerInterface $em,
        private CurrencyRepository $repository,
        private CurrencyRateRepository $repositoryRate,
    ) {
    }

    /**
     * @throws NotFoundException
     */
    public function create(CurrencyRateCreationDto $dto): CurrencyRate
    {
        $currency = $this->repository->findById($dto->currencyId);
        if ($currency === null) {
            throw new NotFoundException(Currency::class, $dto->currencyId);
        }

        $currencyRate = new CurrencyRate($currency, $dto->value, $dto->date);

        $this->repositoryRate->add($currencyRate);
        $this->em->flush();

        return $currencyRate;
    }
}
