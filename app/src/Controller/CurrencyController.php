<?php

namespace App\Controller;

use App\DTO\CurrencyCreationDto;
use App\DTO\CurrencyUpdateDto;
use App\Exception\FailedCurrencyCreationException;
use App\Exception\NotFoundException;
use App\Factory\ResponseFactory;
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
        private ResponseFactory $responseFactory,
        private ValidatorInterface $validator,
        private LoggerInterface $logger,
    ) {
    }

    #[Route('/currencies', name: 'create_currency', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $dto = CurrencyCreationDto::createFromArray($data);
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return $this->responseFactory->createResponseBadRequest($errors);
        }

        try {
            $currency = $this->currencyService->create($dto);
        } catch (FailedCurrencyCreationException $exception) {
            $this->logger->error($exception->systemMessage);

            return $this->responseFactory->createResponseInternalServerError($exception->humanMessage);
        }

        return $this->responseFactory->createResponseSuccess(
            [
                'id' => $currency->getId(),
                'num_code' => $currency->getNumCode(),
                'char_code' => $currency->getCharCode(),
                'name' => $currency->getName(),
            ],
        );
    }

    #[Route('/currencies/{id}', name: 'get_currency', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        try {
            $currency = $this->currencyRepository->getById($id);
        } catch (NotFoundException $exception) {
            $this->logger->error($exception->getMessage());

            return $this->responseFactory->createResponseNotFound(['error' => $exception->getMessage()]);
        }

        return $this->responseFactory->createResponseSuccess(
            [
                'id' => $currency->getId(),
                'num_code' => $currency->getNumCode(),
                'char_code' => $currency->getCharCode(),
                'name' => $currency->getName(),
            ],
        );
    }

    #[Route('/currencies/{id}', name: 'update_currency', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $data['id'] = $id;
        $dto = CurrencyUpdateDto::createFromArray($data);

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return $this->responseFactory->createResponseBadRequest($errors);
        }

        $currency = $this->currencyService->update($dto);
        return $this->responseFactory->createResponseSuccess(
            [
                'id' => $currency->getId(),
                'num_code' => $currency->getNumCode(),
                'char_code' => $currency->getCharCode(),
                'name' => $currency->getName(),
            ],
        );
    }

//    #[Route('/currencies/{id}', name: 'delete_currency', requirements: ['id' => '\d+'], methods: ['DELETE'])]
//    public function delete(int $id, Request $request): JsonResponse
//    {
//
//    }
}
