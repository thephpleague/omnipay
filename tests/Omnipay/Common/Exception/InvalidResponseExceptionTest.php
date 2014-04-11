<?php

namespace Omnipay\Common\Exception;

use Omnipay\Tests\TestCase;

class InvalidResponseExceptionTest extends TestCase
{
    public function testConstructWithDefaultMessage()
    {
        $exception = new InvalidResponseException();
        $this->assertSame('Invalid response from payment gateway', $exception->getMessage());
    }

    public function testConstructWithCustomMessage()
    {
        $exception = new InvalidResponseException('Oops');
        $this->assertSame('Oops', $exception->getMessage());
    }
}
