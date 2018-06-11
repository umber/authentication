<?php

declare(strict_types=1);

namespace Umber\Authentication\Token\Key;

/**
 * Load the contents of a key.
 */
interface KeyLoaderInterface
{
    /**
     * Return the contents of the key file.
     */
    public function load(): string;
}
