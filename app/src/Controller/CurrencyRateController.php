<?php

namespace App\Controller;

use App\DTO\CurrencyRateCreationDto;
use App\Factory\ResponseFactory;
use App\Service\CurrencyRateService;
use App\View\CurrencyRateView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class CurrencyRateController extends BaseController
{
    public function __construct(
        private CurrencyRateService $currencyRateService,
        ResponseFactory $responseFactory,
    ) {
        parent::__construct($responseFactory);
    }

    #[Route('/currencies-rate', name: 'create_currencyrate', methods: ['POST'])]
    public function create(CurrencyRateCreationDto $dto): JsonResponse
    {
        $currencyRate = $this->currencyRateService->create($dto);

        $view = new CurrencyRateView($currencyRate);
        return $this->responseFactory->createResponseSuccess($view->toArray());
    }


}
