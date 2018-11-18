<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception;

use Umber\Common\Exception\AbstractMessageRuntimeException;
use Umber\Common\Exception\Hint\CanonicalAwareExceptionInterface;
use Umber\Common\Exception\Hint\HttpAwareExceptionInterface;

use Symfony\Component\HttpFoundation\Response;

use Throwable;

/**
 * {@inheritdoc}
 */
final class UnauthorisedException extends AbstractMessageRuntimeException implements
    CanonicalAwareExceptionInterface,
    HttpAwareExceptionInterface
{
    /**
     * @return UnauthorisedException
     */
    public static function create(?Throwable $previous = null): self
    {
        return new self([], null, $previous);
    }

    /**
     * {@inheritdoc}
     */
    public static function message(): array
    {
        return [
            'Your credentials are invalid.',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function getCanonicalCode(): string
    {
        return 'http.authorisation.unauthorised';
    }

    /**
     * {@inheritdoc}
     */
    public static function getStatusCode(): int
    {
        return Response::HTTP_UNAUTHORIZED;
    }
}
