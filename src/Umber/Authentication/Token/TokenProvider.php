<?php

declare(strict_types=1);

namespace Umber\Authentication\Token;

use Umber\Authentication\Exception\TokenExpiredException;
use Umber\Authentication\Token\Key\KeyStorageResolverInterface;

use Umber\Date\Factory\DateTimeFactoryInterface;

use InvalidArgumentException;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token as ExternalToken;
use OutOfBoundsException;
use RuntimeException;

/**
 * {@inheritdoc}
 */
final class TokenProvider implements TokenProviderInterface
{
    private $keyStorageResolver;
    private $dateTimeFactory;
    private $ttl;

    public function __construct(
        KeyStorageResolverInterface $keyStorageResolver,
        DateTimeFactoryInterface $dateTimeFactory,
        int $ttl
    ) {
        $this->keyStorageResolver = $keyStorageResolver;
        $this->dateTimeFactory = $dateTimeFactory;
        $this->ttl = $ttl;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): TokenInterface
    {
        $time = $this->dateTimeFactory->create()->getTimestamp();

        $builder = new Builder();
        $builder->setIssuedAt($time);
        $builder->setExpiration($time + $this->ttl);

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
            throw new RuntimeException('failed to sign', 0, $exception);
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

        $this->validateTimeToLive($parsed);

        $storage = $this->keyStorageResolver->resolve();

        $key = new Key(
            $storage->getPublicKeyLoader()->load(),
            $storage->getPassPhrase()
        );

        $verified = $parsed->verify(new Sha256(), $key);

        if ($verified === false) {
            throw new RuntimeException('not verified');
        }

        $token = new Token($parsed);

        return $token;
    }

    /**
     * Validate the time TTL is valid for the given token.
     */
    private function validateTimeToLive(ExternalToken $token): void
    {
        try {
            $value = $token->getClaim('exp');
        } catch (OutOfBoundsException $exception) {
            throw TokenExpiredException::create($exception);
        }

        // Expire time should be a unix timestamp to expire at.
        $expiry = (int) $value;
        $time = $this->dateTimeFactory->create()->getTimestamp();

        if ($expiry >= $time) {
            return;
        }

        throw TokenExpiredException::create();
    }
}
