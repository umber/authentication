<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit\Authorisation;

use Umber\Authentication\Authorisation\Permission;
use Umber\Authentication\Exception\Authorisation\Permission\PermissionAbilityNameInvalidException;
use Umber\Authentication\Exception\Authorisation\Permission\PermissionScopeNameInvalidException;

use PHPUnit\Framework\TestCase;

/**
 * @group unit
 * @group authentication
 *
 * @covers \Umber\Authentication\Authorisation\Permission
 */
final class PermissionTest extends TestCase
{
    /**
     * @test
     */
    public function checkBasicUsage(): void
    {
        $permission = new Permission('permission-name', []);

        self::assertEquals('permission-name', $permission->getScope());
        self::assertEquals([], $permission->getAbilities());
    }

    /**
     * @test
     */
    public function withUpperCaseLowerCase(): void
    {
        $permission = new Permission('Permission-NAME-here', []);

        self::assertEquals('permission-name-here', $permission->getScope());
    }

    /**
     * @test
     */
    public function withUpperCaseAbilityLowerCase(): void
    {
        $permission = new Permission('permission-name', [
            'UPPER-CASE',
        ]);

        self::assertEquals('permission-name', $permission->getScope());
        self::assertEquals(['upper-case'], $permission->getAbilities());
    }

    /**
     * @test
     */
    public function withWildcardAbilityRemoveOtherAbilities(): void
    {
        $permission = new Permission('permission-name', [
            'view',
            Permission::WILDCARD,
            'create',
        ]);

        self::assertEquals('permission-name', $permission->getScope());
        self::assertEquals([Permission::WILDCARD], $permission->getAbilities());
    }

    /**
     * @test
     */
    public function checkWithAbilities(): void
    {
        $permission = new Permission('permission-name', [
            'view',
            'create',
        ]);

        $expected = [
            'view',
            'create',
        ];

        self::assertEquals($expected, $permission->getAbilities());
    }

    /**
     * @test
     */
    public function canCheckAbilityMissing(): void
    {
        $permission = new Permission('permission-name', [
            'view',
            'create',
        ]);

        self::assertFalse($permission->hasAbility('delete'));
    }

    /**
     * @test
     */
    public function canCheckAbilityFound(): void
    {
        $permission = new Permission('permission-name', [
            'view',
            'create',
        ]);

        self::assertTrue($permission->hasAbility('view'));
    }

    /**
     * @test
     */
    public function canCheckAbilityWildcard(): void
    {
        $permission = new Permission('permission-name', [
            Permission::WILDCARD,
        ]);

        self::assertTrue($permission->hasAbility('view'));
    }

    /**
     * Data provider.
     *
     * @return string[][]
     */
    public function provideWithInvalidPermissionNameThrow(): array
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
     * @dataProvider provideWithInvalidPermissionNameThrow
     *
     * @covers \Umber\Authentication\Exception\Authorisation\Permission\PermissionScopeNameInvalidException
     */
    public function withInvalidPermissionNameThrow(string $scope): void
    {
        self::expectException(PermissionScopeNameInvalidException::class);
        self::expectExceptionMessage(implode(' ', [
            'A permission scope name should only contain alphabetic characters and hyphens or underscores.',
            sprintf('The permission scope name provided "%s" is invalid.', $scope),
        ]));

        new Permission($scope, []);
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
        $permission = new Permission($name, []);

        self::assertEquals(strtolower($name), $permission->getScope());
    }

    /**
     * Data provider.
     *
     * @return string[][]
     */
    public function provideWithInvalidPermissionAbilityThrow(): array
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
     * @dataProvider provideWithInvalidPermissionAbilityThrow
     *
     * @covers \Umber\Authentication\Exception\Authorisation\Permission\PermissionAbilityNameInvalidException
     */
    public function withInvalidPermissionAbilityThrow(string $ability): void
    {
        self::expectException(PermissionAbilityNameInvalidException::class);
        self::expectExceptionMessage(implode(' ', [
            'A permission ability should only contain alphabetic characters and hyphens or underscores.',
            sprintf('The permission ability "%s" is invalid for scope "permission-name".', $ability),
        ]));

        new Permission('permission-name', [$ability]);
    }

    /**
     * Data provider.
     *
     * @return string[][]
     */
    public function provideWithValidPermissionAbilityAllow(): array
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
     * @dataProvider provideWithValidPermissionAbilityAllow
     */
    public function withValidPermissionAbilityAllow(string $ability): void
    {
        $permission = new Permission('permission-name', [$ability]);

        self::assertEquals(strtolower($ability), $permission->getAbilities()[0]);
    }
}
