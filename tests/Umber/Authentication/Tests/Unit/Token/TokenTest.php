<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit\Token;

use Umber\Common\Exception\ExceptionMessage;

use Umber\Authentication\Exception\Token\TokenMissingDataKeyException;
use Umber\Authentication\Token\Token;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use Lcobucci\JWT\Claim;
use Lcobucci\JWT\Token as ExternalToken;
use ReflectionException;

/**
 * {@inheritdoc}
 */
final class TokenTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Token\Token
     *
     * @throws ReflectionException
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
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Token\Token
     *
     * @throws ReflectionException
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
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Token\Token
     *
     * @throws ReflectionException
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
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Token\Token
     * @covers \Umber\Authentication\Exception\Token\TokenMissingDataKeyException
     *
     * @throws ReflectionException
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
        self::expectExceptionMessage(
            ExceptionMessage::translate(
                TokenMissingDataKeyException::message(),
                ['key' => 'test']
            )
        );

        $token->get('test');
    }

    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Token\Token
     *
     * @throws ReflectionException
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
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Token\Token
     *
     * @throws ReflectionException
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
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Token\Token
     *
     * @throws ReflectionException
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
