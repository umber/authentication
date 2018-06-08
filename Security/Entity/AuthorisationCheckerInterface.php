<?php

declare(strict_types=1);

namespace Umber\Authentication\Security\Entity;

use Umber\Common\Database\EntityInterface;

interface AuthorisationCheckerInterface
{
    /**
     * Check the authorisation against an entity.
     *
     * @param string[] $abilities
     */
    public function check(EntityInterface $entity, array $abilities): bool;
}
