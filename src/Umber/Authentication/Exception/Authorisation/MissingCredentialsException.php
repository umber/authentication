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
        return new self();
    }

    public function __construct()
    {
        $message = 'The authorisation header is missing from the request.';

        parent::__construct($message);
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
