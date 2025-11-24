<?php

namespace App\Service;

use App\DTO\CurrencyCreationDto;
use App\DTO\CurrencyUpdateDto;
use App\Entity\Currency;
use App\Exception\FailedCurrencyCreationException;
use App\Exception\FailedCurrencyUpdateException;
use App\Exception\NotFoundException;
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
        if ($alreadyExistCharCode !== null) {
            throw FailedCurrencyCreationException::charCodeNotUnique($dto->charCode);
        }

        $currency = new Currency($dto->numCode, $dto->charCode, $dto->name);

        $this->repository->add($currency);
        $this->em->flush();

        return $currency;
    }

    public function update(CurrencyUpdateDto $dto): Currency
    {
        $currency = $this->repository->findById($dto->id);
        if ($currency === null) {
            throw new NotFoundException(Currency::class, $dto->id);
        }

        $alreadyExistCurrencyNumCode = $this->repository->findByNumCode($dto->numCode);
        if ($alreadyExistCurrencyNumCode !== null && $alreadyExistCurrencyNumCode->getId() !== $currency->getId()) {
            throw FailedCurrencyUpdateException::numCodeNotUnique($dto->numCode);
        }
        $alreadyExistCurrencyCharCode = $this->repository->findByCharCode($dto->charCode);
        if ($alreadyExistCurrencyCharCode !== null && $alreadyExistCurrencyCharCode->getId() !== $currency->getId()) {
            throw FailedCurrencyUpdateException::charCodeNotUnique($dto->charCode);
        }

        $currency->setNumCode($dto->numCode);
        $currency->setCharCode($dto->charCode);
        $currency->setName($dto->name);

        $this->em->flush();

        return $currency;
    }

    public function delete(int $id): int
    {
        $currency = $this->repository->findById($id);
        if ($currency === null) {
            throw new NotFoundException(Currency::class, $id);
        }

        $currencyId = $this->repository->delete($currency);
        $this->em->flush();

        return $currencyId;
    }
}
