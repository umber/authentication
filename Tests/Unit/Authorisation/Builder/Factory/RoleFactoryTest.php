<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit\Authorisation\Builder\Factory;

use Umber\Authentication\Authorisation\Builder\Factory\RoleFactory;
use Umber\Authentication\Authorisation\Permission;
use Umber\Authentication\Authorisation\Role;

use PHPUnit\Framework\TestCase;

/**
 * {@inheritdoc}
 */
final class RoleFactoryTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Authorisation\Builder\Factory\RoleFactory
     */
    public function canCreateEmptyRole(): void
    {
        $role = (new RoleFactory())->create('manager', []);

        self::assertInstanceOf(Role::class, $role);
        self::assertEquals('manager', $role->getName());
        self::assertEquals([], $role->getPassivePermissions());
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Authorisation\Builder\Factory\RoleFactory
     */
    public function canCreateWithPassivePermissions(): void
    {
        $permission = new Permission('product', ['view', 'create']);

        $role = (new RoleFactory())->create('manager', [
            $permission,
        ]);

        self::assertInstanceOf(Role::class, $role);
        self::assertEquals('manager', $role->getName());

        $expected = [
            $permission,
        ];

        self::assertEquals($expected, $role->getPassivePermissions());
    }
}
