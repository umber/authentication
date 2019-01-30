<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Authorisation;

use Umber\Authentication\Enum\AuthenticationErrorEnum;

use Umber\Http\Hint\HttpAwareExceptionInterface;
use Umber\Http\Hint\HttpCanonicalAwareExceptionInterface;

use Symfony\Component\HttpFoundation\Response;

use Exception;

/**
 * An exception thrown when the authorisation header is missing.
 */
final class MissingCredentialsException extends Exception implements
    HttpCanonicalAwareExceptionInterface,
    HttpAwareExceptionInterface
{
    /**
     * @return MissingCredentialsException
     */
    public static function create(): self
    {
        $message = 'The authorisation header is missing from the request.';

        return new self($message);
    }

    /**
     * {@inheritdoc}
     */
    public static function getCanonicalCode(): string
    {
        return AuthenticationErrorEnum::CANONICAL_MISSING_CREDENTIALS;
    }

    /**
     * {@inheritdoc}
     */
    public static function getStatusCode(): int
    {
        return Response::HTTP_UNAUTHORIZED;
    }
}
