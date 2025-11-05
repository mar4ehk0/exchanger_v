<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;

class HelloWorldController extends BaseController
{
    #[Route('/hello-world', name: 'hello_world', methods: ['GET'])]
    public function helloWorldAction()
    {
        return $this->createResponseSuccess('Hello World!');
    }
}
