<?php

namespace App\HttpClient;

use App\DTO\CBRFCurrencyRateDto;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CurrencyRateHttpClient
{
    public function __construct(
        private HttpClientInterface $client,
        private string $cbRFUrl,
    ) {
    }

    public function request(): CBRFCurrencyRateDto
    {
        $response = $this->client->request(
            'GET',
            $this->cbRFUrl,
        );

        $statusCode = $response->getStatusCode();
        // сделать политику повторов со sleep
        if ($statusCode !== Response::HTTP_OK) {
            throw new Exception('Can not retrieve response status code');
        }

        $headers = $response->getHeaders();
        if (empty($headers['content-type']) && !empty($headers['content-type'][0])) {
            throw new Exception('Content-Type in headers is empty');
        }

        $contentType = $headers['content-type'][0];
        $data = $response->getContent();

        return new CBRFCurrencyRateDto($contentType, $data);
    }
}
