<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception;

use Umber\Common\Exception\AbstractRuntimeException;
use Umber\Common\Exception\Hint\CanonicalAwareExceptionInterface;
use Umber\Common\Exception\Hint\HttpAwareExceptionInterface;

use Symfony\Component\HttpFoundation\Response;

/**
 * {@inheritdoc}
 */
final class PermissionDeniedException extends AbstractRuntimeException implements
    CanonicalAwareExceptionInterface,
    HttpAwareExceptionInterface
{
    /**
     * @return PermissionDeniedException
     */
    public static function create(): self
    {
        return new self([]);
    }

    /**
     * {@inheritdoc}
     */
    public static function getMessageTemplate(): array
    {
        return [
            'You require greater permissions to perform this action.',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function getCanonicalCode(): string
    {
        return 'http.authorisation.permission.denied';
    }

    /**
     * {@inheritdoc}
     */
    public static function getStatusCode(): int
    {
        return Response::HTTP_FORBIDDEN;
    }
}
