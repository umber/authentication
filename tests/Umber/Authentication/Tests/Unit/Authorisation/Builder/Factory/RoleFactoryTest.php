<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit\Authorisation\Builder\Factory;

use Umber\Authentication\Authorisation\Builder\Factory\RoleFactory;
use Umber\Authentication\Authorisation\Permission;
use Umber\Authentication\Authorisation\Role;

use PHPUnit\Framework\TestCase;

/**
 * @group unit
 * @group authentication
 *
 * @covers \Umber\Authentication\Authorisation\Builder\Factory\RoleFactory
 */
final class RoleFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function canCreateEmptyRole(): void
    {
        $role = (new RoleFactory())->create('manager', []);

        self::assertInstanceOf(Role::class, $role);
        self::assertEquals('MANAGER', $role->getName());
        self::assertEquals([], $role->getPassivePermissions());
    }

    /**
     * @test
     */
    public function canCreateWithPassivePermissions(): void
    {
        $permission = new Permission('product', ['view', 'create']);

        $role = (new RoleFactory())->create('manager', [
            $permission,
        ]);

        self::assertInstanceOf(Role::class, $role);
        self::assertEquals('MANAGER', $role->getName());

        $expected = [
            $permission,
        ];

        self::assertEquals($expected, $role->getPassivePermissions());
    }
}
