<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit\Framework\Method\Header;

use Umber\Common\Exception\ExceptionMessageHelper;

use Umber\Authentication\Exception\Authorisation\MissingCredentialsException;
use Umber\Authentication\Framework\Method\Header\RequestAuthorisationHeader;

use Symfony\Component\HttpFoundation\Request;

use PHPUnit\Framework\TestCase;

/**
 * {@inheritdoc}
 */
final class RequestAuthorisationHeaderTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Framework\Method\Header\RequestAuthorisationHeader
     */
    public function canConstructBasic(): void
    {
        $request = new Request();
        $request->headers->set(RequestAuthorisationHeader::AUTHORISATION_HEADER, 'some-type some-value');

        $header = new RequestAuthorisationHeader($request);

        self::assertEquals('some-type', $header->getType());
        self::assertEquals('some-value', $header->getCredentials());
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Framework\Method\Header\RequestAuthorisationHeader
     */
    public function canHandleTypeCase(): void
    {
        $request = new Request();
        $request->headers->set(RequestAuthorisationHeader::AUTHORISATION_HEADER, 'Bearer SomeValueHERE');

        $header = new RequestAuthorisationHeader($request);

        self::assertEquals('bearer', $header->getType());
        self::assertEquals('SomeValueHERE', $header->getCredentials());
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Framework\Method\Header\RequestAuthorisationHeader
     */
    public function canCastString(): void
    {
        $request = new Request();
        $request->headers->set(RequestAuthorisationHeader::AUTHORISATION_HEADER, 'Bearer some-value');

        $header = new RequestAuthorisationHeader($request);

        $expected = 'Bearer some-value';

        self::assertEquals($expected, (string) $header);
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Framework\Method\Header\RequestAuthorisationHeader
     */
    public function withMissingAuthorisationHeaderThrow(): void
    {
        self::expectException(MissingCredentialsException::class);
        self::expectExceptionMessage(ExceptionMessageHelper::translate(
            MissingCredentialsException::getMessageTemplate()
        ));

        new RequestAuthorisationHeader(new Request());
    }
}
