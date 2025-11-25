<?php

namespace App\Controller;

use App\DTO\CurrencyCreationDto;
use App\DTO\CurrencyUpdateDto;
use App\Exception\FailedCurrencyCreationException;
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
        ResponseFactory $responseFactory,
        private ValidatorInterface $validator,
        private LoggerInterface $logger,
    ) {
        parent::__construct($responseFactory);
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
        $currency = $this->currencyRepository->getById($id);

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
        // added some comments 12312312 3123 123 12

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

    #[Route('/currencies/{id}', name: 'delete_currency', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $currencyId = $this->currencyService->delete($id);

        return $this->responseFactory->createResponseSuccess(
            [
                'id' => $currencyId,
                'status' => 'deleted',
            ]
        );
    }
}
