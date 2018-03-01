<?php

namespace DominionEnterprises;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \DominionEnterprises\HttpException
 */
final class HttpExceptionTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct()
     * @covers ::getUserMessage()
     */
    public function userMessage()
    {
        $eWithNull = new HttpException('message', 1, 1, null, null);
        $eWithUserMessage = new HttpException('message', 1, 1, null, 'a user message');

        $this->assertSame('message', $eWithNull->getUserMessage());
        $this->assertSame('a user message', $eWithUserMessage->getUserMessage());
    }

    /**
     * @test
     * @covers ::__construct()
     * @covers ::getHttpStatusCode()
     */
    public function httpCode()
    {
        $e = new HttpException('message', 1);
        $this->assertSame(1, $e->getHttpStatusCode());
    }
}
