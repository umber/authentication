<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception;

use Umber\Authentication\Enum\AuthenticationErrorEnum;

use Umber\Http\Hint\HttpAwareExceptionInterface;
use Umber\Http\Hint\HttpCanonicalAwareExceptionInterface;

use Symfony\Component\HttpFoundation\Response;

use Exception;
use Throwable;

/**
 * An unauthorised exception thrown whilst attempting to authenticate.
 *
 * Exceptions defined in the umber bundles are framework agnostic and therefore should be
 * handled accordingly depending on your framework. For example if you are using this
 * authentication code in Symfony you should wrap this exception in HttpException.
 */
final class UnauthorisedException extends Exception implements
    HttpCanonicalAwareExceptionInterface,
    HttpAwareExceptionInterface
{
    /**
     * @return UnauthorisedException
     */
    public static function create(?Throwable $previous = null): self
    {
        $message = 'Your credentials are invalid.';

        return new self($message, 0, $previous);
    }

    /**
     * {@inheritdoc}
     */
    public static function getCanonicalCode(): string
    {
        return AuthenticationErrorEnum::CANONICAL_UNAUTHORISED;
    }

    /**
     * {@inheritdoc}
     */
    public static function getStatusCode(): int
    {
        return Response::HTTP_UNAUTHORIZED;
    }
}
