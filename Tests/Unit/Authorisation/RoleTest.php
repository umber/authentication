<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit\Authorisation;

use Umber\Common\Exception\ExceptionMessageHelper;

use Umber\Authentication\Authorisation\Role;
use Umber\Authentication\Exception\Authorisation\Role\RoleNameInvalidException;

use PHPUnit\Framework\TestCase;

/**
 * {@inheritdoc}
 */
final class RoleTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Authorisation\Role
     */
    public function checkBasicUsage(): void
    {
        $role = new Role('role-name', []);

        self::assertEquals('role-name', $role->getName());
        self::assertEquals([], $role->getPassivePermissions());
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Authorisation\Role
     */
    public function withUpperCaseLowerCase(): void
    {
        $role = new Role('Role-NAME-here', []);

        self::assertEquals('role-name-here', $role->getName());
    }

    /**
     * @return string[][]
     */
    public function provideWithInvalidRoleNameThrow(): array
    {
        return [
            ['has.dot'],
            ['has/slash'],
            ['question?'],
            ['??'],
            ['01123'],

            ['-starting-with-hyphen'],
            ['ending-with-hyphen-'],

            ['_starting_with_underscore'],
            ['ending_with_underscore_'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider provideWithInvalidRoleNameThrow
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Authorisation\Role
     * @covers \Umber\Authentication\Exception\Authorisation\Role\RoleNameInvalidException
     */
    public function withInvalidRoleNameThrow(string $name): void
    {
        self::expectException(RoleNameInvalidException::class);
        self::expectExceptionMessage(
            ExceptionMessageHelper::translate(
                RoleNameInvalidException::getMessageTemplate(),
                ['name' => $name]
            )
        );

        new Role($name, []);
    }

    /**
     * @return string[][]
     */
    public function provideWithValidNameAllow(): array
    {
        return [
            ['word'],
            ['with-hyphen'],
            ['WITH_UNDERSCORE'],
            ['Com-COMBINATION_tON'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider provideWithValidNameAllow
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Authorisation\Role
     */
    public function withValidNameAllow(string $name): void
    {
        $role = new Role($name, []);

        self::assertEquals(strtolower($name), $role->getName());
    }
}
