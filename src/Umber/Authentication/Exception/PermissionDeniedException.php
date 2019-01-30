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
 * An exception thrown when the authenticated user is missing permissions.
 *
 * Exceptions defined in the umber bundles are framework agnostic and therefore should be
 * handled accordingly depending on your framework. For example if you are using this
 * authentication code in Symfony you should wrap this exception in HttpException.
 */
final class PermissionDeniedException extends Exception implements
    HttpCanonicalAwareExceptionInterface,
    HttpAwareExceptionInterface
{
    /**
     * @return PermissionDeniedException
     */
    public static function create(?Throwable $parent = null): self
    {
        $message = 'You require greater permissions to perform this action.';

        return new self($message, 0, $parent);
    }

    /**
     * {@inheritdoc}
     */
    public static function getCanonicalCode(): string
    {
        return AuthenticationErrorEnum::CANONICAL_FORBIDDEN_DENIED;
    }

    /**
     * {@inheritdoc}
     */
    public static function getStatusCode(): int
    {
        return Response::HTTP_FORBIDDEN;
    }
}
