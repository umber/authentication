<?php

declare(strict_types=1);

namespace Umber\Authentication\Token;

use Umber\Authentication\Exception\Token\TokenExpiredException;
use Umber\Authentication\Exception\Token\TokenNotVerifiedException;

/**
 * Create and process authentication tokens.
 */
interface TokenProviderInterface
{
    /**
     * Create an authentication token.
     *
     * @param string[] $data
     *
     * @throws TokenNotVerifiedException
     */
    public function create(array $data): TokenInterface;

    /**
     * Parse an authentication token.
     *
     * @throws TokenExpiredException
     * @throws TokenNotVerifiedException
     */
    public function parse(string $serialised): TokenInterface;
}
