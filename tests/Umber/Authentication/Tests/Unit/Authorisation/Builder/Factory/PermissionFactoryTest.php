<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit\Authorisation\Builder\Factory;

use Umber\Authentication\Authorisation\Builder\Factory\PermissionFactory;
use Umber\Authentication\Authorisation\Permission;
use Umber\Authentication\Exception\Authorisation\Permission\PermissionSerialisationNameInvalidException;

use PHPUnit\Framework\TestCase;

/**
 * @group unit
 * @group authentication
 *
 * @covers \Umber\Authentication\Authorisation\Builder\Factory\PermissionFactory
 */
final class PermissionFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function canCreatePermission(): void
    {
        $permission = (new PermissionFactory())->create('product', ['view']);

        self::assertInstanceOf(Permission::class, $permission);
        self::assertEquals('product', $permission->getScope());
        self::assertEquals(['view'], $permission->getAbilities());
    }

    /**
     * @test
     */
    public function canCreatePermissionFromString(): void
    {
        $permission = (new PermissionFactory())->createFromString('product:view');

        self::assertInstanceOf(Permission::class, $permission);
        self::assertEquals('product', $permission->getScope());
        self::assertEquals(['view'], $permission->getAbilities());
    }

    /**
     * @test
     *
     * @covers \Umber\Authentication\Exception\Authorisation\Permission\PermissionSerialisationNameInvalidException
     */
    public function withPermissionStringInvalidThrow(): void
    {
        self::expectException(PermissionSerialisationNameInvalidException::class);
        self::expectExceptionMessage(implode(' ', [
            'A transportable permission name should contain its ability.',
            'This is done using the format "permission:ability". Multiple abilities are possible through multiple entries of the same name.',
            'The permission "product/view" is invalid.',
        ]));

        (new PermissionFactory())->createFromString('product/view');
    }
}
