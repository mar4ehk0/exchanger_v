<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class BaseController extends AbstractController
{
    public function createResponseSuccess(mixed $data): JsonResponse
    {
        return $this->json($data);
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

        return $this->json($baseResponse, Response::HTTP_BAD_REQUEST);
    }

    public function createResponseInternalServerError(mixed $data): JsonResponse
    {
        return $this->json($data, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
