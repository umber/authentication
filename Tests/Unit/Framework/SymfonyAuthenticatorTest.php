<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit\Framework;

use Umber\Common\Exception\ExceptionMessageHelper;

use Umber\Authentication\Authenticator;
use Umber\Authentication\Authorisation\Builder\Resolver\AuthorisationHierarchyResolverInterface;
use Umber\Authentication\Exception\UnauthorisedException;
use Umber\Authentication\Framework\Method\Header\RequestAuthorisationHeader;
use Umber\Authentication\Framework\Modifier\AuthenticatorRoleModifierInterface;
use Umber\Authentication\Framework\Modifier\NullAuthenticatorRoleModifier;
use Umber\Authentication\Framework\SymfonyAuthenticator;
use Umber\Authentication\Resolver\Credential\User\UserCredential;
use Umber\Authentication\Resolver\CredentialResolverInterface;
use Umber\Authentication\Storage\CredentialStorageInterface;
use Umber\Authentication\Tests\Fixture\AuthorisationHierarchyFixture;
use Umber\Authentication\Tests\Model\UserTestModel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserProviderInterface;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * {@inheritdoc}
 */
final class SymfonyAuthenticatorTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Framework\SymfonyAuthenticator
     * @covers \Umber\Authentication\Framework\Modifier\NullAuthenticatorRoleModifier
     *
     * @throws \ReflectionException
     */
    public function withInvalidTokenNoSupport(): void
    {
        /** @var AuthorisationHierarchyResolverInterface|MockObject $authorisationHierarchyResolver */
        $authorisationHierarchyResolver = $this->createMock(AuthorisationHierarchyResolverInterface::class);

        /** @var CredentialResolverInterface|MockObject $credentialResolver */
        $credentialResolver = $this->createMock(CredentialResolverInterface::class);

        /** @var CredentialStorageInterface|MockObject $credentialStorage */
        $credentialStorage = $this->createMock(CredentialStorageInterface::class);

        $authenticator = new Authenticator(
            $authorisationHierarchyResolver,
            $credentialResolver,
            $credentialStorage
        );

        $modifier = new NullAuthenticatorRoleModifier();
        $symfony = new SymfonyAuthenticator($authenticator, $modifier);

        /** @var TokenInterface|MockObject $token */
        $token = $this->createMock(TokenInterface::class);

        self::assertFalse($symfony->supportsToken($token, 'provider'));
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Framework\SymfonyAuthenticator
     * @covers \Umber\Authentication\Framework\Modifier\NullAuthenticatorRoleModifier
     *
     * @throws \ReflectionException
     */
    public function canSupportPreAuthenticatedTokenOnly(): void
    {
        /** @var AuthorisationHierarchyResolverInterface|MockObject $authorisationHierarchyResolver */
        $authorisationHierarchyResolver = $this->createMock(AuthorisationHierarchyResolverInterface::class);
        $authorisationHierarchyResolver->expects(self::never())
            ->method('resolve');

        /** @var CredentialResolverInterface|MockObject $credentialResolver */
        $credentialResolver = $this->createMock(CredentialResolverInterface::class);
        $credentialResolver->expects(self::never())
            ->method('resolve');

        /** @var CredentialStorageInterface|MockObject $credentialStorage */
        $credentialStorage = $this->createMock(CredentialStorageInterface::class);

        $authenticator = new Authenticator(
            $authorisationHierarchyResolver,
            $credentialResolver,
            $credentialStorage
        );

        $modifier = new NullAuthenticatorRoleModifier();
        $symfony = new SymfonyAuthenticator($authenticator, $modifier);

        $token = new PreAuthenticatedToken(
            'user',
            'credentials',
            'provider',
            []
        );

        self::assertTrue($symfony->supportsToken($token, 'provider'));
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Framework\SymfonyAuthenticator
     * @covers \Umber\Authentication\Framework\Modifier\NullAuthenticatorRoleModifier
     *
     * @throws \ReflectionException
     */
    public function canCreatePreAuthenticatedToken(): void
    {
        $user = new UserTestModel();

        /** @var AuthorisationHierarchyResolverInterface|MockObject $authorisationHierarchyResolver */
        $authorisationHierarchyResolver = $this->createMock(AuthorisationHierarchyResolverInterface::class);
        $authorisationHierarchyResolver->expects(self::once())
            ->method('resolve')
            ->willReturn(
                AuthorisationHierarchyFixture::create()
            );

        /** @var CredentialResolverInterface|MockObject $credentialResolver */
        $credentialResolver = $this->createMock(CredentialResolverInterface::class);
        $credentialResolver->expects(self::once())
            ->method('resolve')
            ->willReturn(
                new UserCredential($user)
            );

        /** @var CredentialStorageInterface|MockObject $credentialStorage */
        $credentialStorage = $this->createMock(CredentialStorageInterface::class);
        $credentialStorage->expects(self::once())
            ->method('authorise');

        $authenticator = new Authenticator(
            $authorisationHierarchyResolver,
            $credentialResolver,
            $credentialStorage
        );

        $modifier = new NullAuthenticatorRoleModifier();
        $symfony = new SymfonyAuthenticator($authenticator, $modifier);

        $request = new Request();
        $request->headers->set(RequestAuthorisationHeader::AUTHORISATION_HEADER, 'Bearer some-value');

        $token = $symfony->createToken($request, 'provider');

        self::assertInstanceOf(PreAuthenticatedToken::class, $token);
        self::assertEquals('provider', $token->getProviderKey());
        self::assertEquals('some-value', $token->getCredentials());
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Framework\SymfonyAuthenticator
     * @covers \Umber\Authentication\Framework\Modifier\NullAuthenticatorRoleModifier
     * @covers \Umber\Authentication\Exception\UnauthorisedException
     *
     * @throws \ReflectionException
     */
    public function withMalformedRequestHeaderThrowUnauthorised(): void
    {
        /** @var AuthorisationHierarchyResolverInterface|MockObject $authorisationHierarchyResolver */
        $authorisationHierarchyResolver = $this->createMock(AuthorisationHierarchyResolverInterface::class);
        $authorisationHierarchyResolver->expects(self::never())
            ->method('resolve');

        /** @var CredentialResolverInterface|MockObject $credentialResolver */
        $credentialResolver = $this->createMock(CredentialResolverInterface::class);
        $credentialResolver->expects(self::never())
            ->method('resolve');

        /** @var CredentialStorageInterface|MockObject $credentialStorage */
        $credentialStorage = $this->createMock(CredentialStorageInterface::class);
        $credentialStorage->expects(self::never())
            ->method('authorise');

        $authenticator = new Authenticator(
            $authorisationHierarchyResolver,
            $credentialResolver,
            $credentialStorage
        );

        $modifier = new NullAuthenticatorRoleModifier();
        $symfony = new SymfonyAuthenticator($authenticator, $modifier);

        $request = new Request();
        $request->headers->set(RequestAuthorisationHeader::AUTHORISATION_HEADER, 'malformed-value');

        self::expectException(UnauthorisedException::class);
        self::expectExceptionMessage(
            ExceptionMessageHelper::translate(
                UnauthorisedException::getMessageTemplate()
            )
        );

        $symfony->createToken($request, 'provider');
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Framework\SymfonyAuthenticator
     * @covers \Umber\Authentication\Framework\Modifier\NullAuthenticatorRoleModifier
     * @covers \Umber\Authentication\Exception\UnauthorisedException
     *
     * @throws \ReflectionException
     */
    public function withMissingRequestHeaderThrowUnauthorised(): void
    {
        /** @var AuthorisationHierarchyResolverInterface|MockObject $authorisationHierarchyResolver */
        $authorisationHierarchyResolver = $this->createMock(AuthorisationHierarchyResolverInterface::class);
        $authorisationHierarchyResolver->expects(self::never())
            ->method('resolve');

        /** @var CredentialResolverInterface|MockObject $credentialResolver */
        $credentialResolver = $this->createMock(CredentialResolverInterface::class);
        $credentialResolver->expects(self::never())
            ->method('resolve');

        /** @var CredentialStorageInterface|MockObject $credentialStorage */
        $credentialStorage = $this->createMock(CredentialStorageInterface::class);
        $credentialStorage->expects(self::never())
            ->method('authorise');

        $authenticator = new Authenticator(
            $authorisationHierarchyResolver,
            $credentialResolver,
            $credentialStorage
        );

        $modifier = new NullAuthenticatorRoleModifier();
        $symfony = new SymfonyAuthenticator($authenticator, $modifier);

        $request = new Request();

        self::expectException(UnauthorisedException::class);
        self::expectExceptionMessage(
            ExceptionMessageHelper::translate(
                UnauthorisedException::getMessageTemplate()
            )
        );

        $symfony->createToken($request, 'provider');
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Framework\SymfonyAuthenticator
     * @covers \Umber\Authentication\Framework\Modifier\NullAuthenticatorRoleModifier
     *
     * @throws \ReflectionException
     */
    public function canAuthenticateToken(): void
    {
        $user = new UserTestModel();

        /** @var AuthorisationHierarchyResolverInterface|MockObject $authorisationHierarchyResolver */
        $authorisationHierarchyResolver = $this->createMock(AuthorisationHierarchyResolverInterface::class);
        $authorisationHierarchyResolver->expects(self::never())
            ->method('resolve');

        /** @var CredentialResolverInterface|MockObject $credentialResolver */
        $credentialResolver = $this->createMock(CredentialResolverInterface::class);
        $credentialResolver->expects(self::never())
            ->method('resolve');

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

        $modifier = new NullAuthenticatorRoleModifier();
        $symfony = new SymfonyAuthenticator($authenticator, $modifier);

        /** @var UserProviderInterface|MockObject $userProvider */
        $userProvider = $this->createMock(UserProviderInterface::class);

        $token = new PreAuthenticatedToken(
            'user',
            'credentials',
            'provider',
            []
        );

        $token = $symfony->authenticateToken($token, $userProvider, 'provider');

        self::assertInstanceOf(PreAuthenticatedToken::class, $token);
        self::assertEquals('provider', $token->getProviderKey());
        self::assertEquals('credentials', $token->getCredentials());
        self::assertSame($user, $token->getUser());
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Framework\SymfonyAuthenticator
     *
     * @throws \ReflectionException
     */
    public function canAuthenticateTokenModifiedRoles(): void
    {
        $user = new UserTestModel();

        /** @var AuthorisationHierarchyResolverInterface|MockObject $authorisationHierarchyResolver */
        $authorisationHierarchyResolver = $this->createMock(AuthorisationHierarchyResolverInterface::class);
        $authorisationHierarchyResolver->expects(self::never())
            ->method('resolve');

        /** @var CredentialResolverInterface|MockObject $credentialResolver */
        $credentialResolver = $this->createMock(CredentialResolverInterface::class);
        $credentialResolver->expects(self::never())
            ->method('resolve');

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

        /** @var AuthenticatorRoleModifierInterface|MockObject $modifier */
        $modifier = $this->createMock(AuthenticatorRoleModifierInterface::class);
        $modifier->expects(self::once())
            ->method('modify')
            ->willReturn(['MODIFIED_ROLE']);

        $symfony = new SymfonyAuthenticator($authenticator, $modifier);

        /** @var UserProviderInterface|MockObject $userProvider */
        $userProvider = $this->createMock(UserProviderInterface::class);

        $token = new PreAuthenticatedToken(
            'user',
            'credentials',
            'provider',
            []
        );

        $token = $symfony->authenticateToken($token, $userProvider, 'provider');

        $expected = [
            new Role('MODIFIED_ROLE'),
        ];

        self::assertEquals($expected, $token->getRoles());
    }
}
