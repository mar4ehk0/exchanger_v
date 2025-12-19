<?php

namespace App\Tests\Functional;

use LogicException;
use App\Tests\Support\FunctionalTester;

use function array_keys;
use function array_values;
use function dd;
use function dump;
use function explode;
use function http_build_query;
use function json_decode;
use function str_replace;

abstract class AbstractEndpointClass
{
    abstract protected function getEndpoint(): Endpoint;

    protected FunctionalTester $actor;

    public function _before(FunctionalTester $I): void
    {
        $endpoint = $this->getEndpoint();
        if (empty($endpoint->method) || empty($endpoint->path)) {
            throw new LogicException(static::class . '::Endpoint should be defined');
        }

        $this->actor = $I;
    }

    protected function sendRequest(
        array $placeholders = [],
        array $bodyParameters = [],
        array $queryParameters = []
    ) {
        $this->send($placeholders, $bodyParameters, $queryParameters);

        return json_decode($this->actor->grabResponse(), true) ?? [];
    }

    private function send(
        array $placeholders,
        array $bodyParameters = [],
        array $queryParameters = []
    ): void {
        $endpoint = $this->getEndpoint();
        $path = $endpoint->path;

        if ($placeholders !== []) {
            $path = str_replace(array_keys($placeholders), array_values($placeholders), $endpoint->path);
        }

        if ($queryParameters !== []) {
            $path .= '?' . http_build_query($queryParameters);
        }

        $this->actor->haveHttpHeader('Content-Type', 'application/json');
        $this->actor->send($endpoint->method, $path, $bodyParameters);
    }
}
