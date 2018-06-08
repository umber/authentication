<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit;

use Umber\Authentication\Authenticator;
use Umber\Authentication\Authorisation\Builder\Resolver\AuthorisationHierarchyResolverInterface;
use Umber\Authentication\Method\Header\AuthorisationHeader;
use Umber\Authentication\Prototype\UserInterface;
use Umber\Authentication\Resolver\Credential\CredentialInterface;
use Umber\Authentication\Resolver\Credential\User\UserCredentialInterface;
use Umber\Authentication\Resolver\CredentialResolverInterface;
use Umber\Authentication\Storage\CredentialStorageInterface;
use Umber\Authentication\Tests\Fixture\AuthorisationHierarchyFixture;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * {@inheritdoc}
 */
final class AuthenticatorTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Authenticator
     *
     * @throws \ReflectionException
     */
    public function withNotAuthenticatedNotAuthenticated(): void
    {
        /** @var AuthorisationHierarchyResolverInterface|MockObject $authorisationHierarchyResolver */
        $authorisationHierarchyResolver = $this->createMock(AuthorisationHierarchyResolverInterface::class);

        /** @var CredentialResolverInterface|MockObject $credentialResolver */
        $credentialResolver = $this->createMock(CredentialResolverInterface::class);

        /** @var CredentialStorageInterface|MockObject $credentialStorage */
        $credentialStorage = $this->createMock(CredentialStorageInterface::class);
        $credentialStorage->expects(self::once())
            ->method('isAuthenticated')
            ->willReturn(false);

        $authenticator = new Authenticator(
            $authorisationHierarchyResolver,
            $credentialResolver,
            $credentialStorage
        );

        self::assertFalse($authenticator->isAuthenticated());
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Authenticator
     *
     * @throws \ReflectionException
     */
    public function withStorageAuthenticatedGetUser(): void
    {
        /** @var UserInterface|MockObject $user */
        $user = $this->createMock(UserInterface::class);

        /** @var AuthorisationHierarchyResolverInterface|MockObject $authorisationHierarchyResolver */
        $authorisationHierarchyResolver = $this->createMock(AuthorisationHierarchyResolverInterface::class);

        /** @var CredentialResolverInterface|MockObject $credentialResolver */
        $credentialResolver = $this->createMock(CredentialResolverInterface::class);

        /** @var CredentialStorageInterface|MockObject $credentialStorage */
        $credentialStorage = $this->createMock(CredentialStorageInterface::class);
        $credentialStorage->expects(self::once())
            ->method('getUser')
            ->willReturn($user);

        $authenticator = new Authenticator(
            $authorisationHierarchyResolver,
            $credentialResolver,
            $credentialStorage
        );

        self::assertSame($user, $authenticator->getUser());
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Authenticator
     *
     * @throws \ReflectionException
     */
    public function canAuthenticateTokenWithCredentials(): void
    {
        /** @var AuthorisationHierarchyResolverInterface|MockObject $authorisationHierarchyResolver */
        $authorisationHierarchyResolver = $this->createMock(AuthorisationHierarchyResolverInterface::class);
        $authorisationHierarchyResolver->expects(self::once())
            ->method('resolve')
            ->willReturn(
                AuthorisationHierarchyFixture::create()
            );

        /** @var CredentialInterface|MockObject $credentials */
        $credentials = $this->createMock(CredentialInterface::class);
        $credentials->expects(self::once())
            ->method('getAuthorisationRoles')
            ->willReturn([]);

        $credentials->expects(self::once())
            ->method('getAuthorisationPermissions')
            ->willReturn([]);

        /** @var CredentialResolverInterface|MockObject $credentialResolver */
        $credentialResolver = $this->createMock(CredentialResolverInterface::class);
        $credentialResolver->expects(self::once())
            ->method('resolve')
            ->willReturn($credentials);

        /** @var CredentialStorageInterface|MockObject $credentialStorage */
        $credentialStorage = $this->createMock(CredentialStorageInterface::class);
        $credentialStorage->expects(self::once())
            ->method('authorise');

        $authenticator = new Authenticator(
            $authorisationHierarchyResolver,
            $credentialResolver,
            $credentialStorage
        );

        $authenticator->authenticate(new AuthorisationHeader('bearer', 'some-value'));
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Authenticator
     *
     * @throws \ReflectionException
     */
    public function canAuthenticateTokenWithUserCredentials(): void
    {
        /** @var AuthorisationHierarchyResolverInterface|MockObject $authorisationHierarchyResolver */
        $authorisationHierarchyResolver = $this->createMock(AuthorisationHierarchyResolverInterface::class);
        $authorisationHierarchyResolver->expects(self::once())
            ->method('resolve')
            ->willReturn(
                AuthorisationHierarchyFixture::create()
            );

        /** @var UserCredentialInterface|MockObject $credentials */
        $credentials = $this->createMock(UserCredentialInterface::class);
        $credentials->expects(self::never())
            ->method('getUser');

        $credentials->expects(self::once())
            ->method('getAuthorisationRoles')
            ->willReturn([]);

        $credentials->expects(self::once())
            ->method('getAuthorisationPermissions')
            ->willReturn([]);

        /** @var CredentialResolverInterface|MockObject $credentialResolver */
        $credentialResolver = $this->createMock(CredentialResolverInterface::class);
        $credentialResolver->expects(self::once())
            ->method('resolve')
            ->willReturn($credentials);

        /** @var CredentialStorageInterface|MockObject $credentialStorage */
        $credentialStorage = $this->createMock(CredentialStorageInterface::class);
        $credentialStorage->expects(self::once())
            ->method('authorise');

        $authenticator = new Authenticator(
            $authorisationHierarchyResolver,
            $credentialResolver,
            $credentialStorage
        );

        $authenticator->authenticate(new AuthorisationHeader('bearer', 'some-value'));
    }
}
