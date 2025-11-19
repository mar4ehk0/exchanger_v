<?php

namespace App\Factory;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ResponseFactory
{
    public function createResponseSuccess(mixed $data): JsonResponse
    {
        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function createResponseBadRequest(ConstraintViolationListInterface $constraintViolationList): JsonResponse
    {
        $baseResponse = [];

        $errors = [];
        foreach ($constraintViolationList as $violation) {
            $errors[] = [
                'code' => 'WRONG_VALUE',
                'message' => $violation->getMessage(),
                'field' => $violation->getPropertyPath(),
            ];
        }

        if (count($errors) > 0) {
            $baseResponse['error']['validation_errors'] = $errors;
        }

        return new JsonResponse($baseResponse, Response::HTTP_BAD_REQUEST);
    }

    public function createResponseInternalServerError(mixed $data): JsonResponse
    {
        return new JsonResponse($data, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function createResponseNotFound(mixed $data): JsonResponse
    {
        return new JsonResponse($data, Response::HTTP_NOT_FOUND);
    }
}
