<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit\Authorisation\Credential;

use Umber\Authentication\Authorisation\Credential\CredentialAwareAuthorisation;
use Umber\Authentication\Authorisation\Permission;
use Umber\Authentication\Resolver\Credential\CredentialInterface;
use Umber\Authentication\Tests\Fixture\AuthorisationHierarchyFixture;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 * @group authentication
 *
 * @covers \Umber\Authentication\Authorisation\Credential\CredentialAwareAuthorisation
 */
final class CredentialAwareAuthorisationTest extends TestCase
{
    /**
     * @test
     */
    public function checkBasicUsage(): void
    {
        /** @var CredentialInterface|MockObject $credentials */
        $credentials = $this->createMock(CredentialInterface::class);
        $credentials->expects(self::once())
            ->method('getAuthorisationRoles')
            ->willReturn([]);

        $credentials->expects(self::once())
            ->method('getAuthorisationPermissions')
            ->willReturn([]);

        $hierarchy = AuthorisationHierarchyFixture::create();
        $authorisation = new CredentialAwareAuthorisation($credentials, $hierarchy);

        self::assertSame($credentials, $authorisation->getCredentials());

        self::assertEquals([], $authorisation->getRoles());
        self::assertEquals([], $authorisation->getPermissions());
        self::assertEquals([], $authorisation->getPassivePermissions());
    }

    /**
     * @test
     */
    public function canCheckHasRoleMissing(): void
    {
        /** @var CredentialInterface|MockObject $credentials */
        $credentials = $this->createMock(CredentialInterface::class);
        $credentials->expects(self::once())
            ->method('getAuthorisationRoles')
            ->willReturn([
                'manager',
                'admin',
            ]);

        $credentials->expects(self::once())
            ->method('getAuthorisationPermissions')
            ->willReturn([]);

        $hierarchy = AuthorisationHierarchyFixture::create();
        $authorisation = new CredentialAwareAuthorisation($credentials, $hierarchy);

        self::assertFalse($authorisation->hasRole('system'));
    }

    /**
     * @test
     */
    public function canCheckHasRoleFound(): void
    {
        /** @var CredentialInterface|MockObject $credentials */
        $credentials = $this->createMock(CredentialInterface::class);
        $credentials->expects(self::once())
            ->method('getAuthorisationRoles')
            ->willReturn([
                'manager',
                'admin',
            ]);

        $credentials->expects(self::once())
            ->method('getAuthorisationPermissions')
            ->willReturn([]);

        $hierarchy = AuthorisationHierarchyFixture::create();
        $authorisation = new CredentialAwareAuthorisation($credentials, $hierarchy);

        self::assertTrue($authorisation->hasRole('manager'));
    }

    /**
     * @test
     */
    public function canCheckHasPermissionMissing(): void
    {
        /** @var CredentialInterface|MockObject $credentials */
        $credentials = $this->createMock(CredentialInterface::class);
        $credentials->expects(self::once())
            ->method('getAuthorisationRoles')
            ->willReturn([]);

        $credentials->expects(self::once())
            ->method('getAuthorisationPermissions')
            ->willReturn([
                'product:view',
                'blog:view',
            ]);

        $hierarchy = AuthorisationHierarchyFixture::create();
        $authorisation = new CredentialAwareAuthorisation($credentials, $hierarchy);

        self::assertFalse($authorisation->hasPermission('user', 'view'));
    }

    /**
     * @test
     */
    public function canCheckHasPermissionFound(): void
    {
        /** @var CredentialInterface|MockObject $credentials */
        $credentials = $this->createMock(CredentialInterface::class);
        $credentials->expects(self::once())
            ->method('getAuthorisationRoles')
            ->willReturn([]);

        $credentials->expects(self::once())
            ->method('getAuthorisationPermissions')
            ->willReturn([
                'product:view',
                'blog:view',
            ]);

        $hierarchy = AuthorisationHierarchyFixture::create();
        $authorisation = new CredentialAwareAuthorisation($credentials, $hierarchy);

        self::assertTrue($authorisation->hasPermission('product', 'view'));
    }

    /**
     * @test
     */
    public function canGetPassivePermissions(): void
    {
        /** @var CredentialInterface|MockObject $credentials */
        $credentials = $this->createMock(CredentialInterface::class);
        $credentials->expects(self::once())
            ->method('getAuthorisationRoles')
            ->willReturn([
                'manager',
            ]);

        $credentials->expects(self::once())
            ->method('getAuthorisationPermissions')
            ->willReturn([]);

        $hierarchy = AuthorisationHierarchyFixture::create();
        $authorisation = new CredentialAwareAuthorisation($credentials, $hierarchy);

        $expected = [
            new Permission('blog', ['view']),
            new Permission('product', ['create', 'view']),
        ];

        self::assertEquals($expected, $authorisation->getPassivePermissions());
    }

    /**
     * @test
     */
    public function canCheckHasPassivePermission(): void
    {
        /** @var CredentialInterface|MockObject $credentials */
        $credentials = $this->createMock(CredentialInterface::class);
        $credentials->expects(self::once())
            ->method('getAuthorisationRoles')
            ->willReturn([
                'manager',
            ]);

        $credentials->expects(self::once())
            ->method('getAuthorisationPermissions')
            ->willReturn([]);

        $hierarchy = AuthorisationHierarchyFixture::create();
        $authorisation = new CredentialAwareAuthorisation($credentials, $hierarchy);

        self::assertTrue($authorisation->hasPermission('product', 'view'));
    }

    /**
     * @test
     */
    public function canExpandRolePassivePermissions(): void
    {
        /** @var CredentialInterface|MockObject $credentials */
        $credentials = $this->createMock(CredentialInterface::class);
        $credentials->expects(self::once())
            ->method('getAuthorisationRoles')
            ->willReturn([
                'manager',
            ]);

        $credentials->expects(self::once())
            ->method('getAuthorisationPermissions')
            ->willReturn([
                'product:view',
            ]);

        $hierarchy = AuthorisationHierarchyFixture::create();
        $authorisation = new CredentialAwareAuthorisation($credentials, $hierarchy);

        $permissions = [
            new Permission('product', ['view']),
        ];

        $passive = [
            new Permission('blog', ['view']),
            new Permission('product', ['create', 'view']),
        ];

        self::assertEquals($permissions, $authorisation->getPermissions());
        self::assertEquals($passive, $authorisation->getPassivePermissions());
    }
}
