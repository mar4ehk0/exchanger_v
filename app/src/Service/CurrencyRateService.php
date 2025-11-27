<?php

namespace App\Service;

use App\DTO\CurrencyRateCreationDto;
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
     * @throws FailedCurrencyCreationException
     */
    public function create(CurrencyRateCreationDto $dto): CurrencyRate
    {
        $currency = $this->repository->findById($dto->currency_id);
        if ($currency === null) {
            throw new NotFoundException(Currency::class, $dto->currency_id);
        }

        $date = new \DateTimeImmutable($dto->date);

        $currencyRate = new CurrencyRate($currency, $dto->value, $date);

        $this->repositoryRate->add($currencyRate);
        $this->em->flush();

        return $currencyRate;
    }
}
