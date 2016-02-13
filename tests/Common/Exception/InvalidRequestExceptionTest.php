<?php

namespace League\Omnipay\Common\Exception;

use League\Omnipay\Tests\TestCase;

class InvalidRequestExceptionTest extends TestCase
{
    public function testConstruct()
    {
        $exception = new InvalidRequestException('Oops');
        $this->assertSame('Oops', $exception->getMessage());
    }
}
