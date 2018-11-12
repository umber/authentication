<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Authorisation;

use Umber\Common\Exception\Hint\CanonicalAwareExceptionInterface;
use Umber\Common\Exception\Hint\HttpAwareExceptionInterface;

use Umber\Exception\Message\AbstractMessageRuntimeException;

use Symfony\Component\HttpFoundation\Response;

/**
 * An exception thrown when the authorisation header is missing.
 */
final class MissingCredentialsException extends AbstractMessageRuntimeException implements
    CanonicalAwareExceptionInterface,
    HttpAwareExceptionInterface
{
    /**
     * @return MissingCredentialsException
     */
    public static function create(): self
    {
        return new self([]);
    }

    /**
     * {@inheritdoc}
     */
    public static function message(): array
    {
        return [
            'The authorisation header is missing from the request.',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function getCanonicalCode(): string
    {
        return 'http.authorisation.missing_credentials';
    }

    /**
     * {@inheritdoc}
     */
    public static function getStatusCode(): int
    {
        return Response::HTTP_UNAUTHORIZED;
    }
}
