<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit\Method\Header;

use Umber\Authentication\Exception\Method\Header\MalformedAuthorisationHeaderException;
use Umber\Authentication\Method\Header\StringAuthorisationHeader;

use PHPUnit\Framework\TestCase;

/**
 * @group unit
 * @group authentication
 *
 * @covers \Umber\Authentication\Method\Header\StringAuthorisationHeader
 */
final class StringAuthorisationHeaderTest extends TestCase
{
    /**
     * @test
     *
     * @covers \Umber\Authentication\Exception\Method\Header\MalformedAuthorisationHeaderException
     */
    public function cannotConstructMalformedString(): void
    {
        self::expectException(MalformedAuthorisationHeaderException::class);
        self::expectExceptionMessage('The authentication header "invalid" is malformed.');

        new StringAuthorisationHeader('invalid');
    }

    /**
     * @test
     */
    public function canConstructBasic(): void
    {
        $header = new StringAuthorisationHeader('some-type some-value');

        self::assertEquals('some-type', $header->getType());
        self::assertEquals('some-value', $header->getCredentials());
    }

    /**
     * @test
     */
    public function canHandleTypeCase(): void
    {
        $header = new StringAuthorisationHeader('BEARer SomeValueHERE');

        self::assertEquals('bearer', $header->getType());
        self::assertEquals('SomeValueHERE', $header->getCredentials());
    }

    /**
     * @test
     */
    public function canCastString(): void
    {
        $header = new StringAuthorisationHeader('bearer some-value');

        $expected = 'Bearer some-value';

        self::assertEquals($expected, (string) $header);
    }
}
