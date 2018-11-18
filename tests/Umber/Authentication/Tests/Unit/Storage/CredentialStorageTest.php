<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit\Storage;

use Umber\Common\Exception\ExceptionMessage;

use Umber\Authentication\Authorisation\Credential\CredentialAwareAuthorisationInterface;
use Umber\Authentication\Exception\Resolver\CannotResolveAuthenticatedUserException;
use Umber\Authentication\Exception\UnauthorisedException;
use Umber\Authentication\Prototype\UserInterface;
use Umber\Authentication\Resolver\Credential\CredentialInterface;
use Umber\Authentication\Resolver\Credential\User\UserCredentialInterface;
use Umber\Authentication\Storage\CredentialStorage;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use ReflectionException;

/**
 * {@inheritdoc}
 */
final class CredentialStorageTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Storage\CredentialStorage
     */
    public function withFreshInstanceNotAuthenticated(): void
    {
        $storage = new CredentialStorage();

        self::assertFalse($storage->isAuthenticated());
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Storage\CredentialStorage
     * @covers \Umber\Authentication\Exception\UnauthorisedException
     */
    public function withFreshInstanceCannotGetCredentials(): void
    {
        $storage = new CredentialStorage();

        self::expectException(UnauthorisedException::class);
        self::expectExceptionMessage(
            ExceptionMessage::translate(
                UnauthorisedException::message()
            )
        );

        $storage->getCredentials();
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Storage\CredentialStorage
     * @covers \Umber\Authentication\Exception\UnauthorisedException
     */
    public function withFreshInstanceCannotGetAuthorisation(): void
    {
        $storage = new CredentialStorage();

        self::expectException(UnauthorisedException::class);
        self::expectExceptionMessage(
            ExceptionMessage::translate(
                UnauthorisedException::message()
            )
        );

        $storage->getAuthorisation();
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Storage\CredentialStorage
     * @covers \Umber\Authentication\Exception\UnauthorisedException
     */
    public function withFreshInstanceCannotGetUser(): void
    {
        $storage = new CredentialStorage();

        self::expectException(UnauthorisedException::class);
        self::expectExceptionMessage(
            ExceptionMessage::translate(
                UnauthorisedException::message()
            )
        );

        $storage->getUser();
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Storage\CredentialStorage
     *
     * @throws ReflectionException
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
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Storage\CredentialStorage
     *
     * @throws ReflectionException
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
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Storage\CredentialStorage
     *
     * @throws ReflectionException
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

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Storage\CredentialStorage
     * @covers \Umber\Authentication\Exception\Resolver\CannotResolveAuthenticatedUserException
     *
     * @throws ReflectionException
     */
    public function withBasicAuthorisationCannotGetUser(): void
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

        self::expectException(CannotResolveAuthenticatedUserException::class);
        self::expectExceptionMessage(
            ExceptionMessage::translate(
                CannotResolveAuthenticatedUserException::message()
            )
        );

        $storage->getUser();
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Storage\CredentialStorage
     *
     * @throws ReflectionException
     */
    public function withUserCredentialsCanGetUser(): void
    {
        /** @var UserInterface|MockObject $user */
        $user = $this->createMock(UserInterface::class);

        /** @var UserCredentialInterface|MockObject $credentials */
        $credentials = $this->createMock(UserCredentialInterface::class);
        $credentials->expects(self::once())
            ->method('getUser')
            ->willReturn($user);

        /** @var CredentialAwareAuthorisationInterface|MockObject $authorisation */
        $authorisation = $this->createMock(CredentialAwareAuthorisationInterface::class);
        $authorisation->expects(self::once())
            ->method('getCredentials')
            ->willReturn($credentials);

        $storage = new CredentialStorage();
        $storage->authorise($authorisation);

        self::assertSame($user, $storage->getUser());
    }
}
