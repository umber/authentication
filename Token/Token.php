<?php

declare(strict_types=1);

namespace Umber\Authentication\Token;

use Umber\Authentication\Exception\Token\TokenMissingDataKeyException;

use Lcobucci\JWT\Claim;
use Lcobucci\JWT\Token as ExternalToken;

/**
 * A JWT token implementation.
 */
final class Token implements TokenInterface
{
    private $token;

    /** @var string[] */
    private $data = [];

    public function __construct(ExternalToken $token)
    {
        $this->token = $token;

        /** @var Claim[] $claims */
        $claims = $this->token->getClaims();

        foreach ($claims as $claim) {
            $this->data[$claim->getName()] = $claim->getValue();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key): string
    {
        if ($this->has($key) === false) {
            throw TokenMissingDataKeyException::create($key);
        }

        return $this->data[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return (string) $this->token;
    }

    /**
     * Magic conversion to string.
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
