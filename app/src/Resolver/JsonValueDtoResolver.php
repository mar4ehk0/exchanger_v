<?php

namespace App\Resolver;

use App\Exception\JsonBodyDtoResolverException;
use App\Interface\JsonBodyDtoRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;

use function is_subclass_of;

class JsonValueDtoResolver implements ValueResolverInterface
{
    private const CONTENT_TYPE_JSON = 'json';

    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $type = $argument->getType();

        if (
            null === $type
            || !is_subclass_of($type, JsonBodyDtoRequestInterface::class)
            || self::CONTENT_TYPE_JSON !== $request->getContentTypeFormat()
        ) {
            return [];
        }

        $data = $request->toArray();
        if ($request->attributes->has('id')) {
            $data['id'] = (int) $request->attributes->get('id');
        }

        try {
            $dto = $this->serializer->denormalize($data, $type);

            return [$dto];
        } catch (\Throwable $e) {
            throw new JsonBodyDtoResolverException($e);
        }
    }
}
