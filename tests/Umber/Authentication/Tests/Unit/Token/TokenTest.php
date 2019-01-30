<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit\Token;

use Umber\Authentication\Exception\Token\TokenMissingDataKeyException;
use Umber\Authentication\Token\Token;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use Lcobucci\JWT\Claim;
use Lcobucci\JWT\Token as ExternalToken;

/**
 * @group unit
 * @group authentication
 *
 * @covers \Umber\Authentication\Token\Token
 */
final class TokenTest extends TestCase
{
    /**
     * @test
     */
    public function checkBasicUsage(): void
    {
        /** @var ExternalToken|MockObject $external */
        $external = $this->createMock(ExternalToken::class);
        $external->expects(self::once())
            ->method('getClaims')
            ->willReturn([]);

        $token = new Token($external);

        self::assertEquals([], $token->getData());
    }

    /**
     * @test
     */
    public function canCheckHasDataNotFound(): void
    {
        /** @var ExternalToken|MockObject $external */
        $external = $this->createMock(ExternalToken::class);
        $external->expects(self::once())
            ->method('getClaims')
            ->willReturn([]);

        $token = new Token($external);

        self::assertEquals([], $token->getData());
        self::assertFalse($token->has('test'));
    }

    /**
     * @test
     */
    public function canCheckHasDataFound(): void
    {
        /** @var MockObject|Claim $claim */
        $claim = $this->createMock(Claim::class);
        $claim->expects(self::once())
            ->method('getName')
            ->willReturn('foo');

        $claim->expects(self::once())
            ->method('getValue')
            ->willReturn('bar');

        /** @var ExternalToken|MockObject $external */
        $external = $this->createMock(ExternalToken::class);
        $external->expects(self::once())
            ->method('getClaims')
            ->willReturn([
                $claim,
            ]);

        $token = new Token($external);

        $expected = [
            'foo' => 'bar',
        ];

        self::assertEquals($expected, $token->getData());
        self::assertTrue($token->has('foo'));
    }

    /**
     * @test
     *
     * @covers \Umber\Authentication\Exception\Token\TokenMissingDataKeyException
     */
    public function withMissingDataGetThrows(): void
    {
        /** @var ExternalToken|MockObject $external */
        $external = $this->createMock(ExternalToken::class);
        $external->expects(self::once())
            ->method('getClaims')
            ->willReturn([]);

        $token = new Token($external);

        self::expectException(TokenMissingDataKeyException::class);
        self::expectExceptionMessage('The authentication token does not have data "test".');

        $token->get('test');
    }

    /**
     * @test
     */
    public function canGetTokenData(): void
    {
        /** @var MockObject|Claim $claim */
        $claim = $this->createMock(Claim::class);
        $claim->expects(self::once())
            ->method('getName')
            ->willReturn('foo');

        $claim->expects(self::once())
            ->method('getValue')
            ->willReturn('bar');

        /** @var ExternalToken|MockObject $external */
        $external = $this->createMock(ExternalToken::class);
        $external->expects(self::once())
            ->method('getClaims')
            ->willReturn([
                $claim,
            ]);

        $token = new Token($external);

        $expected = [
            'foo' => 'bar',
        ];

        self::assertEquals($expected, $token->getData());
        self::assertEquals('bar', $token->get('foo'));
    }

    /**
     * @test
     */
    public function canConvertExternalTokenToString(): void
    {
        /** @var ExternalToken|MockObject $external */
        $external = $this->createMock(ExternalToken::class);
        $external->expects(self::once())
            ->method('getClaims')
            ->willReturn([]);

        $external->expects(self::once())
            ->method('__toString')
            ->willReturn('called-magic');

        $token = new Token($external);

        self::assertEquals('called-magic', $token->toString());
    }

    /**
     * @test
     */
    public function canMagicConvertExternalTokenToString(): void
    {
        /** @var ExternalToken|MockObject $external */
        $external = $this->createMock(ExternalToken::class);
        $external->expects(self::once())
            ->method('getClaims')
            ->willReturn([]);

        $external->expects(self::once())
            ->method('__toString')
            ->willReturn('called-magic');

        $token = new Token($external);

        self::assertEquals('called-magic', (string) $token);
    }
}
