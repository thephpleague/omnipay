<?php

namespace Omnipay\Common\Exception;

use Omnipay\Tests\TestCase;

class InvalidCreditCardExceptionTest extends TestCase
{
    public function testConstruct()
    {
        $exception = new InvalidCreditCardException('Oops');
        $this->assertSame('Oops', $exception->getMessage());
    }
}
