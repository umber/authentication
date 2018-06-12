<?php

declare(strict_types=1);

namespace Umber\Authentication\Token;

use Umber\Authentication\Token\Key\KeyStorageResolverInterface;

use InvalidArgumentException;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Claim;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;

/**
 * {@inheritdoc}
 */
final class TokenProvider implements TokenProviderInterface
{
    private $keyStorageResolver;
    private $ttl;

    public function __construct(KeyStorageResolverInterface $keyStorageResolver, int $ttl)
    {
        $this->keyStorageResolver = $keyStorageResolver;
        $this->ttl = $ttl;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): TokenInterface
    {
        $builder = new Builder();
        $builder->setIssuedAt(time());
        $builder->setExpiration(time() + $this->ttl);

        foreach ($data as $name => $value) {
            $builder->set($name, $value);
        }

        $storage = $this->keyStorageResolver->resolve();

        $key = new Key(
            $storage->getPrivateKeyLoader()->load(),
            $storage->getPassPhrase()
        );

        try {
            $builder->sign(new Sha256(), $key);
        } catch (InvalidArgumentException $exception) {
            throw new \RuntimeException('failed to sign', 0, $exception);
        }

        $token = new Token($builder->getToken());

        return $token;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(string $serialised): TokenInterface
    {
        $parser = new Parser();
        $parsed = $parser->parse($serialised);

        /** @var Claim[] $claims */
        $claims = $parsed->getClaims();
        $data = [];

        foreach ($claims as $claim) {
            $data[$claim->getName()] = $claim->getValue();
        }

        $storage = $this->keyStorageResolver->resolve();

        $key = new Key(
            $storage->getPublicKeyLoader()->load(),
            $storage->getPassPhrase()
        );

        $verified = $parsed->verify(new Sha256(), $key);

        if ($verified === false) {
            throw new \RuntimeException('not verified');
        }

        $token = new Token($parsed);

        return $token;
    }
}
