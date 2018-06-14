<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit\Authorisation;

use Umber\Authentication\Authorisation\Authorisation;
use Umber\Authentication\Authorisation\Permission;
use Umber\Authentication\Tests\Fixture\AuthorisationHierarchyFixture;

use PHPUnit\Framework\TestCase;

/**
 * {@inheritdoc}
 */
final class AuthorisationTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Authorisation\Authorisation
     */
    public function checkBasicUsage(): void
    {
        $hierarchy = AuthorisationHierarchyFixture::create();
        $authorisation = new Authorisation([], [], $hierarchy);

        self::assertEquals([], $authorisation->getRoles());
        self::assertEquals([], $authorisation->getPermissions());
        self::assertEquals([], $authorisation->getPassivePermissions());
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Authorisation\Authorisation
     */
    public function canCheckHasRoleMissing(): void
    {
        $roles = [
            'manager',
            'admin',
        ];

        $hierarchy = AuthorisationHierarchyFixture::create();
        $authorisation = new Authorisation($roles, [], $hierarchy);

        self::assertFalse($authorisation->hasRole('system'));
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Authorisation\Authorisation
     */
    public function canCheckHasRoleFound(): void
    {
        $roles = [
            'manager',
            'admin',
        ];

        $hierarchy = AuthorisationHierarchyFixture::create();
        $authorisation = new Authorisation($roles, [], $hierarchy);

        self::assertTrue($authorisation->hasRole('MANAGER'));
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Authorisation\Authorisation
     */
    public function canCheckHasPermissionMissing(): void
    {
        $permissions = [
            'product:view',
            'blog:view',
        ];

        $hierarchy = AuthorisationHierarchyFixture::create();
        $authorisation = new Authorisation([], $permissions, $hierarchy);

        self::assertFalse($authorisation->hasPermission('user', 'view'));
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Authorisation\Authorisation
     */
    public function canCheckHasPermissionFound(): void
    {
        $permissions = [
            'product:view',
            'blog:view',
        ];

        $hierarchy = AuthorisationHierarchyFixture::create();
        $authorisation = new Authorisation([], $permissions, $hierarchy);

        self::assertTrue($authorisation->hasPermission('product', 'view'));
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Authorisation\Authorisation
     */
    public function canGetPassivePermissions(): void
    {
        $roles = [
            'manager',
        ];

        $hierarchy = AuthorisationHierarchyFixture::create();
        $authorisation = new Authorisation($roles, [], $hierarchy);

        $expected = [
            new Permission('blog', ['view']),
            new Permission('product', ['create', 'view']),
        ];

        self::assertEquals($expected, $authorisation->getPassivePermissions());
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Authorisation\Authorisation
     */
    public function canCheckHasPassivePermission(): void
    {
        $roles = [
            'manager',
        ];

        $hierarchy = AuthorisationHierarchyFixture::create();
        $authorisation = new Authorisation($roles, [], $hierarchy);

        self::assertTrue($authorisation->hasPermission('product', 'view'));
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Authorisation\Authorisation
     */
    public function canExpandRolePassivePermissions(): void
    {
        $roles = [
            'manager',
        ];

        $permissions = [
            'product:view',
        ];

        $hierarchy = AuthorisationHierarchyFixture::create();
        $authorisation = new Authorisation($roles, $permissions, $hierarchy);

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
