<?php

declare(strict_types=1);

namespace Umber\Authentication\Token\Key\Storage;

use Umber\Authentication\Token\Key\KeyLoaderInterface;

/**
 * A authentication key storage.
 */
final class KeyStorage
{
    /** @var KeyLoaderInterface */
    private $public;

    /** @var KeyLoaderInterface */
    private $private;

    /** @var string|null */
    private $passPhrase = null;

    public function __construct(KeyLoaderInterface $public, KeyLoaderInterface $private, ?string $passPhrase)
    {
        $this->public = $public;
        $this->private = $private;
        $this->passPhrase = $passPhrase;

        if ($this->passPhrase !== '') {
            return;
        }

        $this->passPhrase = null;
    }

    /**
     * Return the public key loader.
     */
    public function getPublicKeyLoader(): KeyLoaderInterface
    {
        return $this->public;
    }

    /**
     * Return the private key loader,
     */
    public function getPrivateKeyLoader(): KeyLoaderInterface
    {
        return $this->private;
    }

    /**
     * Return the pass phrase.
     *
     * Returns null if no pass phrase has been provided.
     */
    public function getPassPhrase(): ?string
    {
        return $this->passPhrase;
    }
}
