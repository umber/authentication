<?php

declare(strict_types=1);

namespace Umber\Authentication\Security;

use Umber\Authentication\Exception\PermissionDeniedException;
use Umber\Authentication\Exception\UnauthorisedException;

interface SecurityInterface
{
    /**
     * Check if the permission is granted.
     *
     * That is the authenticated user has the permission (scope and ability) provided.
     *
     * @throws UnauthorisedException
     * @throws PermissionDeniedException
     */
    public function hasPermission(string $scope, string $abilitiy): void;

    /**
     * Check if the ability is granted on the given object.
     *
     * The entity will be resolved to a permission scope and the ability checked. This is different to the
     * {@link hasPermission()} method as abilities might require further authorisation checks.
     *
     * @param mixed $object
     *
     * @throws PermissionDeniedException
     */
    public function isGranted($object, string ...$abilities): void;
}
