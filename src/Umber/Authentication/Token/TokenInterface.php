<?php

declare(strict_types=1);

namespace Umber\Authentication\Token;

use Umber\Authentication\Exception\Token\TokenMissingDataKeyException;

/**
 * An authentication token.
 */
interface TokenInterface
{
    /**
     * Return the token data by key.
     *
     * @throws TokenMissingDataKeyException
     */
    public function get(string $key): string;

    /**
     * Check for token data by key.
     */
    public function has(string $key): bool;

    /**
     * Return the token data.
     *
     * @return string[]
     */
    public function getData(): array;

    /**
     * Return the token as a string.
     */
    public function toString(): string;

    /**
     * Magic conversion to string.
     */
    public function __toString(): string;
}
