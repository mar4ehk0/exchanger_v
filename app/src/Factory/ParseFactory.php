<?php

namespace App\Factory;

use App\Interface\ParserInterface;
use App\Parser\JsonParser;
use App\Parser\XmlParser;
use Exception;
use Symfony\Component\Serializer\SerializerInterface;

use function sprintf;
use function str_contains;

class ParseFactory
{

    public function __construct(
        private SerializerInterface $serializer
    ) {
    }

    private const CONTENT_TYPE_XML = 'xml';
    private const CONTENT_TYPE_JSON = 'application/json';

    public function create(string $contentType): ParserInterface
    {
        if (str_contains($contentType, self::CONTENT_TYPE_XML)) {
            return new XmlParser($this->serializer);
        }
        if (str_contains($contentType, self::CONTENT_TYPE_JSON)) {
            return new JsonParser();
        }

        throw new Exception(sprintf('Parser does not implemented for content type: %s', $contentType));
    }
}
