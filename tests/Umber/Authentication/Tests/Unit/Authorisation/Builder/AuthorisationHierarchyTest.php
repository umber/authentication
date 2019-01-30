<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit\Authorisation\Builder;

use Umber\Authentication\Authorisation\Builder\AuthorisationHierarchy;
use Umber\Authentication\Authorisation\Builder\Factory\PermissionFactory;
use Umber\Authentication\Authorisation\Builder\Factory\RoleFactory;
use Umber\Authentication\Authorisation\Permission;
use Umber\Authentication\Authorisation\Role;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\DuplicatePermissionScopeException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\DuplicateRoleException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionAbilityNotFoundException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionMissingAbilitiesException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionScopeNotFoundException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\RoleNotFoundException;

use PHPUnit\Framework\TestCase;

/**
 * @group unit
 * @group authentication
 *
 * @covers \Umber\Authentication\Authorisation\Builder\AuthorisationHierarchy
 */
final class AuthorisationHierarchyTest extends TestCase
{
    /** @var AuthorisationHierarchy */
    private $hierarchy;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        $this->hierarchy = new AuthorisationHierarchy(
            new RoleFactory(),
            new PermissionFactory()
        );
    }

    /**
     * @test
     */
    public function canGetEmptyRoles(): void
    {
        self::assertEquals([], $this->hierarchy->getRoles());
    }

    /**
     * @test
     */
    public function canGetEmptyPermissions(): void
    {
        self::assertEquals([], $this->hierarchy->getPermissions());
    }

    /**
     * @test
     */
    public function canNotFindMissingRole(): void
    {
        self::assertFalse($this->hierarchy->hasRole('admin'));
    }

    /**
     * @test
     */
    public function canNotFindMissingPermission(): void
    {
        self::assertFalse($this->hierarchy->hasPermission('product'));
    }

    /**
     * @test
     *
     * @covers \Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\RoleNotFoundException
     */
    public function whenGetRoleMissingThrow(): void
    {
        self::expectException(RoleNotFoundException::class);
        self::expectExceptionMessage('The role "admin" was not found.');

        $this->hierarchy->getRole('admin');
    }

    /**
     * @test
     *
     * @covers \Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionScopeNotFoundException
     */
    public function whenGetPermissionMissingThrow(): void
    {
        self::expectException(PermissionScopeNotFoundException::class);
        self::expectExceptionMessage('The permission scope "product" was not found.');

        $this->hierarchy->getPermission('product');
    }

    /**
     * @test
     *
     * @covers \Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionMissingAbilitiesException
     */
    public function whenCreatePermissionEmptyAbilitiesThrow(): void
    {
        self::expectException(PermissionMissingAbilitiesException::class);
        self::expectExceptionMessage(implode(' ', [
            'The hierarchy expects that all permissions come with at least one ability.',
            'The permission scope "product" has no abilities assigned to it so is considered useless.',
        ]));

        $this->hierarchy->addPermission('product', []);
    }

    /**
     * @test
     */
    public function canCreatePermission(): void
    {
        $this->hierarchy->addPermission('product', ['view']);

        $expected = [
            new Permission('product', ['view']),
        ];

        self::assertEquals($expected, $this->hierarchy->getPermissions());
    }

    /**
     * @test
     *
     * @covers \Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\DuplicatePermissionScopeException
     */
    public function whenCreatingDuplicatePermissionThrow(): void
    {
        self::expectException(DuplicatePermissionScopeException::class);
        self::expectExceptionMessage(implode(' ', [
            'The hierarchy cannot contain duplicate permission scopes.',
            'The permission scope "product" has already been defined and cannot be overwritten or merged.',
        ]));

        $this->hierarchy->addPermission('product', ['view']);
        $this->hierarchy->addPermission('product', ['view']);
    }

    /**
     * @test
     */
    public function canCheckHasNewPermission(): void
    {
        $this->hierarchy->addPermission('product', ['view']);

        self::assertTrue($this->hierarchy->hasPermission('product'));
        self::assertFalse($this->hierarchy->hasPermission('blog'));
    }

    /**
     * @test
     */
    public function canGetPermissionByScope(): void
    {
        $this->hierarchy->addPermission('product', ['view']);

        $expected = new Permission('product', ['view']);

        self::assertEquals($expected, $this->hierarchy->getPermission('product'));
    }

    /**
     * @test
     *
     * @covers \Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionScopeNotFoundException
     */
    public function whenGetPermissionAbilityMissingScope(): void
    {
        self::expectException(PermissionScopeNotFoundException::class);
        self::expectExceptionMessage('The permission scope "product" was not found.');

        $this->hierarchy->getPermissionAbility('product', 'view');
    }

    /**
     * @test
     *
     * @covers \Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionAbilityNotFoundException
     */
    public function whenGetPermissionAbilityMissingPermissionAbility(): void
    {
        $this->hierarchy->addPermission('product', ['create']);

        self::expectException(PermissionAbilityNotFoundException::class);
        self::expectExceptionMessage('The permission ability "view" was not found against the scope "product".');

        $this->hierarchy->getPermissionAbility('product', 'view');
    }

    /**
     * @test
     */
    public function canGetPermissionAbility(): void
    {
        $this->hierarchy->addPermission('product', [
            'view',
            'create',
            'delete',
        ]);

        $expected = new Permission('product', ['create']);

        self::assertEquals($expected, $this->hierarchy->getPermissionAbility('product', 'create'));
    }

    /**
     * @test
     */
    public function canCreateEmptyRoles(): void
    {
        $this->hierarchy->addRole('manager', [], []);
        $this->hierarchy->addRole('admin', [], []);

        $expected = [
            new Role('manager', []),
            new Role('admin', []),
        ];

        self::assertEquals($expected, $this->hierarchy->getRoles());
    }

    /**
     * @test
     *
     * @covers \Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\DuplicateRoleException
     */
    public function whenCreatingDuplicateRoleThrow(): void
    {
        self::expectException(DuplicateRoleException::class);
        self::expectExceptionMessage(implode(' ', [
            'The hierarchy cannot contain duplicate roles.',
            'The role "manager" has already been defined and cannot be overwritten or merged.',
        ]));

        $this->hierarchy->addRole('manager', [], []);
        $this->hierarchy->addRole('manager', [], []);
    }

    /**
     * @test
     *
     * @covers \Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionScopeNotFoundException
     */
    public function whenRoleWithMissingPermissionThrow(): void
    {
        self::expectException(PermissionScopeNotFoundException::class);
        self::expectExceptionMessage('The permission scope "product" was not found.');

        $this->hierarchy->addRole('manager', [], ['product:view']);
    }

    /**
     * @test
     *
     * @covers \Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionScopeNotFoundException
     */
    public function whenRoleWithMissingPermissionAbilityThrow(): void
    {
        self::expectException(PermissionScopeNotFoundException::class);
        self::expectExceptionMessage('The permission scope "product" was not found.');

        $this->hierarchy->addRole('manager', [], ['product:view']);
    }

    /**
     * @test
     */
    public function canCreateBasicRolesWithPermissions(): void
    {
        $this->hierarchy->addPermission('product', [
            'view',
            'create',
        ]);

        $this->hierarchy->addRole('manager', [], ['product:view']);

        $expected = [
            new Role('manager', [
                new Permission('product', [
                    'view',
                ]),
            ]),
        ];

        self::assertEquals($expected, $this->hierarchy->getRoles());
    }

    /**
     * @test
     */
    public function canCheckRoleByName(): void
    {
        $this->hierarchy->addPermission('product', [
            'view',
            'create',
        ]);

        $this->hierarchy->addRole('manager', [], ['product:view']);

        self::assertTrue($this->hierarchy->hasRole('manager'));
        self::assertFalse($this->hierarchy->hasRole('admin'));
    }

    /**
     * @test
     */
    public function canGetRoleByName(): void
    {
        $this->hierarchy->addPermission('product', [
            'view',
            'create',
        ]);

        $this->hierarchy->addRole('manager', [], ['product:view']);

        $expected = new Role('manager', [
            new Permission('product', [
                'view',
            ]),
        ]);

        self::assertEquals($expected, $this->hierarchy->getRole('manager'));
    }

    /**
     * @test
     */
    public function canResolveInheritedRolePermissions(): void
    {
        $this->hierarchy->addPermission('product', [
            'view',
            'create',
        ]);

        $this->hierarchy->addPermission('blog', [
            'view',
            'create',
            'delete',
        ]);

        $this->hierarchy->addRole('manager', [], ['product:view']);
        $this->hierarchy->addRole('admin', ['manager'], []);

        $expected = new Role('admin', [
            new Permission('product', [
                'view',
            ]),
        ]);

        self::assertEquals($expected, $this->hierarchy->getRole('admin'));
    }

    /**
     * @test
     */
    public function canResolveInheritedRolePermissionsWithOwnPermissions(): void
    {
        $this->hierarchy->addPermission('product', [
            'view',
            'create',
        ]);

        $this->hierarchy->addPermission('blog', [
            'view',
            'create',
            'delete',
        ]);

        $this->hierarchy->addRole('manager', [], ['product:view']);
        $this->hierarchy->addRole('admin', ['manager'], [
            'blog:view',
            'blog:create',
        ]);

        $expected = new Role('admin', [
            new Permission('blog', [
                'create',
                'view',
            ]),
            new Permission('product', [
                'view',
            ]),
        ]);

        self::assertEquals($expected, $this->hierarchy->getRole('admin'));
    }

    /**
     * @test
     */
    public function canResolveRoleArray(): void
    {
        $this->hierarchy->addPermission('product', [
            'view',
            'create',
        ]);

        $this->hierarchy->addPermission('blog', [
            'view',
            'create',
            'delete',
        ]);

        $this->hierarchy->addRole('manager', [], ['product:view']);
        $this->hierarchy->addRole('admin', ['manager'], [
            'blog:view',
            'blog:create',
        ]);

        $expected = [
            new Role('admin', [
                new Permission('blog', [
                    'create',
                    'view',
                ]),
                new Permission('product', [
                    'view',
                ]),
            ]),
        ];

        self::assertEquals($expected, $this->hierarchy->resolveRolesByArray(['admin']));
    }
}
