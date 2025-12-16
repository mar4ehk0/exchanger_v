<?php

namespace App\Controller;

use App\DTO\CurrencyCreationDto;
use App\DTO\CurrencyUpdateDto;
use App\Exception\FailedCurrencyCreationException;
use App\Factory\ResponseFactory;
use App\Repository\CurrencyRepository;
use App\Service\CurrencyService;
use App\View\CurrencyView;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CurrencyController extends BaseController
{
    public function __construct(
        private CurrencyService $currencyService,
        private CurrencyRepository $currencyRepository,
        ResponseFactory $responseFactory,
        private ValidatorInterface $validator,
        private LoggerInterface $logger,
        private SerializerInterface $serializer,
    ) {
        parent::__construct($responseFactory);
    }

    #[Route('/currencies', name: 'create_currency', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), CurrencyCreationDto::class, 'json');

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

        $view = new CurrencyView($currency);
        return $this->responseFactory->createResponseSuccess($view->toArray());
    }

    #[Route('/currencies/{id}', name: 'get_currency', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $currency = $this->currencyRepository->getById($id);

        //        $view = new CurrencyView($currency);

        $currencySerialosed = $this->serializer->serialize($currency, 'json');

        return JsonResponse::fromJsonString($currencySerialosed);
    }

    #[Route('/currencies/{id}', name: 'update_currency', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function update(int $id, CurrencyUpdateDto $dto): JsonResponse
    {
        // added some comments 12312312 3123 123 12
        //        $data = json_decode($request->getContent(), true);
        //        $data['id'] = $id;
        //        $dto = CurrencyUpdateDto::createFromArray($data);

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return $this->responseFactory->createResponseBadRequest($errors);
        }

        $currency = $this->currencyService->update($dto);

        $view = new CurrencyView($currency);

        return $this->responseFactory->createResponseSuccess($view->toArray());
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
