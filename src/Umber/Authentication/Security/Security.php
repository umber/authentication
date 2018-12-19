<?php

declare(strict_types=1);

namespace Umber\Authentication\Security;

use Umber\Database\EntityInterface;

use Umber\Authentication\Exception\PermissionDeniedException;
use Umber\Authentication\Security\Entity\AuthorisationCheckerInterface;
use Umber\Authentication\Storage\CredentialStorageInterface;

/**
 * {@inheritdoc}
 */
final class Security implements SecurityInterface
{
    private $authentication;
    private $checker;

    public function __construct(
        CredentialStorageInterface $authentication,
        AuthorisationCheckerInterface $checker
    ) {
        $this->authentication = $authentication;
        $this->checker = $checker;
    }

    /**
     * {@inheritdoc}
     */
    public function hasPermission(string $scope, string $ability): void
    {
        $authorisation = $this->authentication->getAuthorisation();
        $state = $authorisation->hasPermission($scope, $ability);

        if ($state === true) {
            return;
        }

        throw PermissionDeniedException::create();
    }

    /**
     * {@inheritdoc}
     */
    public function isGranted(EntityInterface $entity, string ...$abilities): void
    {
        $state = $this->checker->check($entity, $abilities);

        if ($state === true) {
            return;
        }

        throw PermissionDeniedException::create();
    }
}
