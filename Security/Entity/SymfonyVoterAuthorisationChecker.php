<?php

declare(strict_types=1);

namespace Umber\Authentication\Security\Entity;

use Umber\Common\Database\EntityInterface;

/**
 * {@inheritdoc}
 */
final class SymfonyVoterAuthorisationChecker implements AuthorisationCheckerInterface
{
    /**
     * {@inheritdoc}
     */
    public function check(EntityInterface $entity, array $abilities): bool
    {
        return true;
    }
}
