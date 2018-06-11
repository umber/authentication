<?php

declare(strict_types=1);

namespace Umber\Authentication\Token\Key\Storage;

use Umber\Authentication\Token\Key\Loader\FileKeyLoader;

/**
 * A authentication key storage.
 */
final class KeyStorage
{
    private $public;
    private $private;
    private $passPhrase;

    public function __construct(FileKeyLoader $public, FileKeyLoader $private, ?string $passPhrase)
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
     * Return the public key.
     */
    public function getPublicKey(): FileKeyLoader
    {
        return $this->public;
    }

    /**
     * Return the private key,
     */
    public function getPrivateKey(): FileKeyLoader
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
