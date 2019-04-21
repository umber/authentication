<?php

declare(strict_types=1);

namespace Umber\Authentication\Security\Entity;

interface AuthorisationCheckerInterface
{
    /**
     * Check the authorisation against an entity.
     *
     * @param mixed $object
     * @param string[] $abilities
     */
    public function check($object, array $abilities): bool;
}
