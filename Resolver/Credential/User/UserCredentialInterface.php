<?php

declare(strict_types=1);

namespace Umber\Authentication\Resolver\Credential\User;

use Umber\Authentication\Prototype\UserInterface;
use Umber\Authentication\Resolver\Credential\CredentialInterface;

/**
 * Resolved user credentials.
 */
interface UserCredentialInterface extends CredentialInterface
{
    /**
     * Return the resolved user.
     */
    public function getUser(): UserInterface;
}
