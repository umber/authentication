<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit\Framework\Symfony\Method\Header;

use Umber\Authentication\Exception\Authorisation\MissingCredentialsException;
use Umber\Authentication\Exception\Method\Header\MalformedAuthorisationHeaderException;
use Umber\Authentication\Framework\Symfony\Method\Header\SymfonyRequestAuthorisationHeader;

use Symfony\Component\HttpFoundation\Request;

use PHPUnit\Framework\TestCase;

/**
 * @group unit
 * @group authentication
 *
 * @covers \Umber\Authentication\Framework\Symfony\Method\Header\SymfonyRequestAuthorisationHeader
 */
final class SymfonyRequestAuthorisationHeaderTest extends TestCase
{
    /**
     * @test
     */
    public function canConstructBasic(): void
    {
        $request = new Request();
        $request->headers->set(SymfonyRequestAuthorisationHeader::AUTHORISATION_HEADER, 'some-type some-value');

        $header = new SymfonyRequestAuthorisationHeader($request);

        self::assertEquals('some-type', $header->getType());
        self::assertEquals('some-value', $header->getCredentials());
    }

    /**
     * @test
     */
    public function canHandleTypeCase(): void
    {
        $request = new Request();
        $request->headers->set(SymfonyRequestAuthorisationHeader::AUTHORISATION_HEADER, 'Bearer SomeValueHERE');

        $header = new SymfonyRequestAuthorisationHeader($request);

        self::assertEquals('bearer', $header->getType());
        self::assertEquals('SomeValueHERE', $header->getCredentials());
    }

    /**
     * @test
     */
    public function canCastString(): void
    {
        $request = new Request();
        $request->headers->set(SymfonyRequestAuthorisationHeader::AUTHORISATION_HEADER, 'Bearer some-value');

        $header = new SymfonyRequestAuthorisationHeader($request);

        $expected = 'Bearer some-value';

        self::assertEquals($expected, (string) $header);
    }

    /**
     * @test
     *
     * @covers \Umber\Authentication\Exception\Authorisation\MissingCredentialsException
     */
    public function withMissingAuthorisationHeaderThrow(): void
    {
        self::expectException(MissingCredentialsException::class);
        self::expectExceptionMessage('The authorisation header is missing from the request.');

        new SymfonyRequestAuthorisationHeader(new Request());
    }

    /**
     * @test
     *
     * @covers \Umber\Authentication\Exception\Method\Header\MalformedAuthorisationHeaderException
     */
    public function withMalformedAuthorisationHeaderThrow(): void
    {
        $request = new Request();
        $request->headers->set(SymfonyRequestAuthorisationHeader::AUTHORISATION_HEADER, 'invalid');

        self::expectException(MalformedAuthorisationHeaderException::class);
        self::expectExceptionMessage('The authentication header "invalid" is malformed.');

        new SymfonyRequestAuthorisationHeader($request);
    }
}
