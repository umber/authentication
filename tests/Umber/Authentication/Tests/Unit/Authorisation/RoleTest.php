<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit\Authorisation;

use Umber\Authentication\Authorisation\Role;
use Umber\Authentication\Exception\Authorisation\Role\RoleNameInvalidException;

use PHPUnit\Framework\TestCase;

/**
 * @group unit
 * @group authentication
 *
 * @covers \Umber\Authentication\Authorisation\Role
 */
final class RoleTest extends TestCase
{
    /**
     * @test
     */
    public function checkBasicUsage(): void
    {
        $role = new Role('role_name', []);

        self::assertEquals('ROLE_NAME', $role->getName());
        self::assertEquals([], $role->getPassivePermissions());
    }

    /**
     * @test
     */
    public function withUpperCaseLowerCase(): void
    {
        $role = new Role('Role_NAME_here', []);

        self::assertEquals('ROLE_NAME_HERE', $role->getName());
    }

    /**
     * Data provider.
     *
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
     * @covers \Umber\Authentication\Exception\Authorisation\Role\RoleNameInvalidException
     */
    public function withInvalidRoleNameThrow(string $name): void
    {
        self::expectException(RoleNameInvalidException::class);
        self::expectExceptionMessage(implode(' ', [
            'A role name should only contain alphabetic characters and hyphens or underscores.',
            sprintf('The role name "%s" is invalid.', $name),
        ]));

        new Role($name, []);
    }

    /**
     * Data provider.
     *
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
     */
    public function withValidNameAllow(string $name): void
    {
        $role = new Role($name, []);

        self::assertEquals(strtoupper($name), $role->getName());
    }
}
