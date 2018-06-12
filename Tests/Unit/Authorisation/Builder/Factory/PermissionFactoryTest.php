<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit\Authorisation\Builder\Factory;

use Umber\Common\Exception\ExceptionMessageHelper;

use Umber\Authentication\Authorisation\Builder\Factory\PermissionFactory;
use Umber\Authentication\Authorisation\Permission;
use Umber\Authentication\Exception\Authorisation\Permission\PermissionSerialisationNameInvalidException;

use PHPUnit\Framework\TestCase;

/**
 * {@inheritdoc}
 */
final class PermissionFactoryTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Authorisation\Builder\Factory\PermissionFactory
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
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Authorisation\Builder\Factory\PermissionFactory
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
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Authorisation\Builder\Factory\PermissionFactory
     * @covers \Umber\Authentication\Exception\Authorisation\Permission\PermissionSerialisationNameInvalidException
     */
    public function withPermissionStringInvalidThrow(): void
    {
        self::expectException(PermissionSerialisationNameInvalidException::class);
        self::expectExceptionMessage(
            ExceptionMessageHelper::translate(
                PermissionSerialisationNameInvalidException::getMessageTemplate(),
                ['permission' => 'product/view']
            )
        );

        (new PermissionFactory())->createFromString('product/view');
    }
}
