<?php

namespace App\Controller;

use App\DTO\CurrencyCreationDto;
use App\Exception\FailedCurrencyCreationException;
use App\Exception\NotFoundException;
use App\Repository\CurrencyRepository;
use App\Service\CurrencyService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CurrencyController extends BaseController
{
    public function __construct(
        private CurrencyService $currencyService,
        private CurrencyRepository $currencyRepository,
        private ValidatorInterface $validator,
        private LoggerInterface $logger
    ) {
    }

    #[Route('/currencies', name: 'create_currency', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $dto = CurrencyCreationDto::createFromArray($data);
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return $this->createResponseBadRequest($errors);
        }

        try {
            $currency = $this->currencyService->create($dto);
        } catch (FailedCurrencyCreationException $exception) {
            $this->logger->error($exception->systemMessage);

            return $this->createResponseInternalServerError($exception->humanMessage);
        }

        return $this->createResponseSuccess(
            [
                'id' => $currency->getId(),
                'num_code' => $currency->getNumCode(),
                'char_code' => $currency->getCharCode(),
                'name' => $currency->getName(),
            ]
        );
    }

    #[Route('/currencies/{id}', name: 'get_currency', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        try {
            $currency = $this->currencyRepository->getById($id);
        } catch (NotFoundException $exception) {
            $this->logger->error($exception->getMessage());

            return $this->createResponseNotFound(['error' => $exception->getMessage()]);
        }


        return $this->createResponseSuccess(
            [
                'id' => $currency->getId(),
                'num_code' => $currency->getNumCode(),
                'char_code' => $currency->getCharCode(),
                'name' => $currency->getName(),
            ]
        );
    }


}
