<?php

namespace App\Listener;

use App\Exception\BaseServiceException;
use App\Exception\DtoExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof DtoExceptionInterface && $exception instanceof BaseServiceException) {
            // такую ошибку показывать только для дев режима для прода скрывать, просто ошибка 400 и лог
            $data['error']['struct_error'] = [
                'code' => 'WRONG_PROPERTY_NAME',
                'message' => $exception->systemMessage,
            ];

            $response = new JsonResponse(
                $data,
                Response::HTTP_BAD_REQUEST
            );

            $event->setResponse($response);
            $this->logger->error($exception->systemMessage);
        }
    }
}
