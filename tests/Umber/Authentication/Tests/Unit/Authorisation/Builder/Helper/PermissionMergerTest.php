<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit\Authorisation\Builder\Helper;

use Umber\Authentication\Authorisation\Builder\Factory\PermissionFactory;
use Umber\Authentication\Authorisation\Builder\Factory\PermissionFactoryInterface;
use Umber\Authentication\Authorisation\Builder\Helper\PermissionMerger;
use Umber\Authentication\Authorisation\Permission;

use PHPUnit\Framework\TestCase;

/**
 * @group unit
 * @group authentication
 *
 * @covers \Umber\Authentication\Authorisation\Builder\Helper\PermissionMerger
 */
final class PermissionMergerTest extends TestCase
{
    /** @var PermissionFactoryInterface */
    protected $factory;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        $this->factory = new PermissionFactory();
    }

    /**
     * @test
     */
    public function canMergeSameRemoveDuplicate(): void
    {
        $input = [
            new Permission('product', ['view']),
            new Permission('product', ['view']),
        ];

        $output = PermissionMerger::merge($this->factory, $input);

        $expected = [
            new Permission('product', ['view']),
        ];

        self::assertEquals($expected, $output);
    }

    /**
     * @test
     */
    public function canMergeSameScopeDifferentAbilities(): void
    {
        $input = [
            new Permission('product', ['view']),
            new Permission('product', ['create']),
        ];

        $output = PermissionMerger::merge($this->factory, $input);

        $expected = [
            new Permission('product', [
                'create',
                'view',
            ]),
        ];

        self::assertEquals($expected, $output);
    }

    /**
     * @test
     */
    public function canMergeSameAbilities(): void
    {
        $input = [
            new Permission('product', ['view', 'create']),
            new Permission('product', ['create', 'delete']),
        ];

        $output = PermissionMerger::merge($this->factory, $input);

        $expected = [
            new Permission('product', [
                'create',
                'delete',
                'view',
            ]),
        ];

        self::assertEquals($expected, $output);
    }
}
