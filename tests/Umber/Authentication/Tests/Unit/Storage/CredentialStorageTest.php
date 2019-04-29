<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit\Storage;

use Umber\Authentication\Authorisation\Credential\CredentialAwareAuthorisationInterface;
use Umber\Authentication\Exception\UnauthorisedException;
use Umber\Authentication\Resolver\Credential\CredentialInterface;
use Umber\Authentication\Storage\CredentialStorage;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 * @group authentication
 *
 * @covers \Umber\Authentication\Storage\CredentialStorage
 */
final class CredentialStorageTest extends TestCase
{
    /**
     * @test
     */
    public function withFreshInstanceNotAuthenticated(): void
    {
        $storage = new CredentialStorage();

        self::assertFalse($storage->isAuthenticated());
    }

    /**
     * @test
     *
     * @covers \Umber\Authentication\Exception\UnauthorisedException
     */
    public function withFreshInstanceCannotGetCredentials(): void
    {
        $storage = new CredentialStorage();

        self::expectException(UnauthorisedException::class);
        self::expectExceptionMessage('Your credentials are invalid.');

        $storage->getCredentials();
    }

    /**
     * @test
     *
     * @covers \Umber\Authentication\Exception\UnauthorisedException
     */
    public function withFreshInstanceCannotGetAuthorisation(): void
    {
        $storage = new CredentialStorage();

        self::expectException(UnauthorisedException::class);
        self::expectExceptionMessage('Your credentials are invalid.');

        $storage->getAuthorisation();
    }

    /**
     * @test
     */
    public function canAuthorise(): void
    {
        /** @var CredentialAwareAuthorisationInterface|MockObject $authorisation */
        $authorisation = $this->createMock(CredentialAwareAuthorisationInterface::class);
        $authorisation->expects(self::never())
            ->method('getCredentials');

        $storage = new CredentialStorage();
        $storage->authorise($authorisation);

        self::assertTrue($storage->isAuthenticated());
    }

    /**
     * @test
     */
    public function canAuthoriseGetCredentials(): void
    {
        /** @var CredentialInterface|MockObject $credentials */
        $credentials = $this->createMock(CredentialInterface::class);

        /** @var CredentialAwareAuthorisationInterface|MockObject $authorisation */
        $authorisation = $this->createMock(CredentialAwareAuthorisationInterface::class);
        $authorisation->expects(self::once())
            ->method('getCredentials')
            ->willReturn($credentials);

        $storage = new CredentialStorage();
        $storage->authorise($authorisation);

        self::assertSame($credentials, $storage->getCredentials());
    }

    /**
     * @test
     */
    public function canAuthoriseGetAuthorisation(): void
    {
        /** @var CredentialAwareAuthorisationInterface|MockObject $authorisation */
        $authorisation = $this->createMock(CredentialAwareAuthorisationInterface::class);
        $authorisation->expects(self::never())
            ->method('getCredentials');

        $storage = new CredentialStorage();
        $storage->authorise($authorisation);

        self::assertSame($authorisation, $storage->getAuthorisation());
    }
}
