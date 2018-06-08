<?php

declare(strict_types=1);

namespace Umber\Authentication\Prototype;

/**
 * A repository interface for the authentication user.
 */
interface UserRepositoryInterface
{
    /**
     * Find and return the user with the given email address.
     *
     * Should the user not be found return null.
     */
    public function findOneByEmail(string $email): ?UserInterface;
}
