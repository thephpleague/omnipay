<?php

namespace League\Omnipay\Common\Exception;

use League\Omnipay\Tests\TestCase;

class BadMethodCallExceptionTest extends TestCase
{
    public function testConstruct()
    {
        $exception = new BadMethodCallException('Oops');
        $this->assertSame('Oops', $exception->getMessage());
    }
}
