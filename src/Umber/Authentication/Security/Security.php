<?php

declare(strict_types=1);

namespace Umber\Authentication\Security;

use Umber\Authentication\Exception\PermissionDeniedException;
use Umber\Authentication\Security\Entity\AuthorisationCheckerInterface;
use Umber\Authentication\Storage\CredentialStorageInterface;

/**
 * {@inheritdoc}
 */
final class Security implements SecurityInterface
{
    /** @var CredentialStorageInterface */
    private $credentials;

    /** @var AuthorisationCheckerInterface */
    private $checker;

    public function __construct(
        CredentialStorageInterface $credentials,
        AuthorisationCheckerInterface $checker
    ) {
        $this->credentials = $credentials;
        $this->checker = $checker;
    }

    /**
     * {@inheritdoc}
     */
    public function hasPermission(string $scope, string $ability): void
    {
        $authorisation = $this->credentials->getAuthorisation();
        $state = $authorisation->hasPermission($scope, $ability);

        if ($state === true) {
            return;
        }

        throw PermissionDeniedException::create();
    }

    /**
     * {@inheritdoc}
     */
    public function isGranted($object, string ...$abilities): void
    {
        $state = $this->checker->check($object, $abilities);

        if ($state === true) {
            return;
        }

        throw PermissionDeniedException::create();
    }
}
