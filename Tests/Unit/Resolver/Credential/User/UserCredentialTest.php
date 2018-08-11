<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit\Resolver\Credential\User;

use Umber\Authentication\Prototype\UserInterface;
use Umber\Authentication\Resolver\Credential\User\UserCredential;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use ReflectionException;

/**
 * {@inheritdoc}
 */
final class UserCredentialTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Resolver\Credential\User\UserCredential
     *
     * @throws ReflectionException
     */
    public function checkBasicUsage(): void
    {
        /** @var UserInterface|MockObject $user */
        $user = $this->createMock(UserInterface::class);
        $user->expects(self::once())
            ->method('getAuthorisationRoles')
            ->willReturn([]);

        $user->expects(self::once())
            ->method('getAuthorisationPermissions')
            ->willReturn([]);

        $credential = new UserCredential($user);

        self::assertSame($user, $credential->getUser());
        self::assertEquals([], $credential->getAuthorisationRoles());
        self::assertEquals([], $credential->getAuthorisationPermissions());
    }
}
