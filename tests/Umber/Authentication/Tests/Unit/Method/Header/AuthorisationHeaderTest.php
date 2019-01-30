<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit\Method\Header;

use Umber\Authentication\Method\Header\AuthorisationHeader;

use PHPUnit\Framework\TestCase;

/**
 * @group unit
 * @group authentication
 *
 * @covers \Umber\Authentication\Method\Header\AuthorisationHeader
 */
final class AuthorisationHeaderTest extends TestCase
{
    /**
     * @test
     */
    public function canConstructBasic(): void
    {
        $header = new AuthorisationHeader('some-type', 'some-value');

        self::assertEquals('some-type', $header->getType());
        self::assertEquals('some-value', $header->getCredentials());
    }

    /**
     * @test
     */
    public function canHandleTypeCase(): void
    {
        $header = new AuthorisationHeader('BEARer', 'SomeValueHERE');

        self::assertEquals('bearer', $header->getType());
        self::assertEquals('SomeValueHERE', $header->getCredentials());
    }

    /**
     * @test
     */
    public function canCastString(): void
    {
        $header = new AuthorisationHeader('bearer', 'some-value');

        $expected = 'Bearer some-value';

        self::assertEquals($expected, (string) $header);
    }
}
