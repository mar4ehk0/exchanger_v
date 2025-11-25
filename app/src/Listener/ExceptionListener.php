<?php

namespace App\Listener;

use App\Exception\BaseServiceException;
use App\Exception\DtoExceptionInterface;
use App\Exception\JsonBodyDtoResolverException;
use App\Exception\NotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

use function preg_match;

// глобальны exception листенер, все не отловленные ошибки будут сюда приходить
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
        if ($exception instanceof NotFoundException) {
            $data['error']['struct_error'] = [
                'code' => 'NOT_FOUND',
                'message' => $exception->getMessage(),
            ];

            $response = new JsonResponse(
                $data,
                Response::HTTP_NOT_FOUND
            );

            $event->setResponse($response);
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
        }
        if ($exception instanceof JsonBodyDtoResolverException) {
            $rawMessage = $exception->getPrevious() ? $exception->getPrevious()->getMessage() : $exception->getMessage();
            $details = $this->formatValidationErrorMessage($rawMessage);

            $data['error']['struct_error'] = [
                'code' => 'WRONG_FORMAT',
                'message' => $details,
            ];

            $response = new JsonResponse(
                $data,
                Response::HTTP_BAD_REQUEST
            );

            $event->setResponse($response);
        }
    }

    private function formatValidationErrorMessage(string $message): array
    {
        if (preg_match('/The type of the "(.+)" attribute for class ".+" must be one of "(.+)" \("(.+)" given\)./', $message, $matches)) {
            return [
                'field' => $matches[1],
                'expected_type' => $matches[2],
                'given_type' => $matches[3],
                'message' => "Field '{$matches[1]}' expects type {$matches[2]}, but {$matches[3]} was provided.",
            ];
        }

        return ['message' => $message];
    }
}
