<?php

namespace App\Controller;

use App\DTO\CurrencyRateCreationDto;
use App\Factory\ResponseFactory;
use App\Repository\CurrencyRepository;
use App\Service\CurrencyRateService;
use App\View\CurrencyRateView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CurrencyRateController extends BaseController
{
    public function __construct(
        private CurrencyRateService $currencyRateService,
        ResponseFactory $responseFactory,
        private SerializerInterface $serializer,
    ) {
        parent::__construct($responseFactory);
    }

    #[Route('/currenciesrate', name: 'create_currencyrate', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), CurrencyRateCreationDto::class, 'json');

        $currencyRate = $this->currencyRateService->create($dto);

        $view = new CurrencyRateView($currencyRate);
        return $this->responseFactory->createResponseSuccess($view->toArray());
    }


}
