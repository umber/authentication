<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit\Framework\Symfony\Method\Header;

use Umber\Common\Exception\ExceptionMessageHelper;

use Umber\Authentication\Exception\Authorisation\MissingCredentialsException;
use Umber\Authentication\Framework\Symfony\Method\Header\SymfonyRequestAuthorisationHeader;

use Symfony\Component\HttpFoundation\Request;

use PHPUnit\Framework\TestCase;

/**
 * {@inheritdoc}
 */
final class SymfonyRequestAuthorisationHeaderTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Framework\Symfony\Method\Header\SymfonyRequestAuthorisationHeader
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
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Framework\Symfony\Method\Header\SymfonyRequestAuthorisationHeader
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
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Framework\Symfony\Method\Header\SymfonyRequestAuthorisationHeader
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
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Framework\Symfony\Method\Header\SymfonyRequestAuthorisationHeader
     * @covers \Umber\Authentication\Exception\Authorisation\MissingCredentialsException
     */
    public function withMissingAuthorisationHeaderThrow(): void
    {
        self::expectException(MissingCredentialsException::class);
        self::expectExceptionMessage(ExceptionMessageHelper::translate(
            MissingCredentialsException::getMessageTemplate()
        ));

        new SymfonyRequestAuthorisationHeader(new Request());
    }
}
