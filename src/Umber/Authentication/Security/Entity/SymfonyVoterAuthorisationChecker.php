<?php

declare(strict_types=1);

namespace Umber\Authentication\Security\Entity;

use Symfony\Component\Security\Core\Security;

/**
 * {@inheritdoc}
 */
final class SymfonyVoterAuthorisationChecker implements AuthorisationCheckerInterface
{
    /** @var Security */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * {@inheritdoc}
     */
    public function check($object, array $abilities): bool
    {
        $granted = $this->security->isGranted($abilities, $object);

        return $granted;
    }
}
