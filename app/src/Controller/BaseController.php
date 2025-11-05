<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class BaseController extends AbstractController
{
    public function createResponseSuccess(mixed $data): JsonResponse
    {
        return $this->json($data);
    }

}
