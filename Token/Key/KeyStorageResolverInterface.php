<?php

declare(strict_types=1);

namespace Umber\Authentication\Token\Key;

use Umber\Authentication\Token\Key\Storage\KeyStorage;

interface KeyStorageResolverInterface
{
    /**
     * Resolve the key storage used for authentication tokens.
     */
    public function resolve(): KeyStorage;
}
