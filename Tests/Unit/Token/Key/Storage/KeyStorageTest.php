<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit\Token\Key\Storage;

use Umber\Authentication\Token\Key\KeyLoaderInterface;
use Umber\Authentication\Token\Key\Storage\KeyStorage;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * {@inheritdoc}
 */
final class KeyStorageTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Token\Key\Storage\KeyStorage
     */
    public function checkBasicUsage(): void
    {
        /** @var KeyLoaderInterface|MockObject $public */
        $public = $this->createMock(KeyLoaderInterface::class);

        /** @var KeyLoaderInterface|MockObject $private */
        $private = $this->createMock(KeyLoaderInterface::class);

        $storage = new KeyStorage($public, $private, null);

        self::assertSame($public, $storage->getPublicKeyLoader());
        self::assertSame($private, $storage->getPrivateKeyLoader());
        self::assertNull($storage->getPassPhrase());
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Token\Key\Storage\KeyStorage
     */
    public function withEmptyStringPassPhraseConvertNull(): void
    {
        /** @var KeyLoaderInterface|MockObject $loader */
        $loader = $this->createMock(KeyLoaderInterface::class);

        $storage = new KeyStorage($loader, $loader, '');

        self::assertNull($storage->getPassPhrase());
    }
}
