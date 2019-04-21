<?php

declare(strict_types=1);

namespace Umber\Authentication\Token\Key\Loader;

use Umber\Authentication\Token\Key\KeyLoaderInterface;

/**
 * A file key loader.
 */
final class FileKeyLoader implements KeyLoaderInterface
{
    /** @var string */
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     */
    public function load(): string
    {
        /** @var string $content */
        $content = file_get_contents($this->path);

        return $content;
    }
}
