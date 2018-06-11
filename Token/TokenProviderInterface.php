<?php

declare(strict_types=1);

namespace Umber\Authentication\Token;

/**
 * Create and process authentication tokens.
 */
interface TokenProviderInterface
{
    /**
     * Create an authentication token.
     *
     * @param string[] $data
     */
    public function create(array $data): TokenInterface;

    /**
     * Parse an authentication token.
     */
    public function parse(string $serialised): TokenInterface;
}
