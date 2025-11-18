<?php

namespace App\Service;

use App\DTO\CurrencyCreationDto;
use App\Entity\Currency;
use App\Exception\FailedCurrencyCreationException;
use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;

class CurrencyService
{
    public function __construct(
        private EntityManagerInterface $em,
        private CurrencyRepository $repository,
    ) {
    }

    public function show(int $id): ?Currency
    {
        $currency = $this->repository->findById($id);

        return $currency;
    }

    /**
     * @throws FailedCurrencyCreationException
     */
    public function create(CurrencyCreationDto $dto): Currency
    {
        $alreadyExistNumCode = $this->repository->findByNumCode($dto->numCode);
        if ($alreadyExistNumCode !== null) {
            throw FailedCurrencyCreationException::numCodeNotUnique($dto->numCode);
        }
        $alreadyExistCharCode = $this->repository->findByCharCode($dto->charCode);
        if ($alreadyExistCharCode) {
            throw FailedCurrencyCreationException::charCodeNotUnique($dto->charCode);
        }

        $currency = new Currency($dto->numCode, $dto->charCode, $dto->name);

        $this->repository->add($currency);
        $this->em->flush();

        return $currency;
    }
}
