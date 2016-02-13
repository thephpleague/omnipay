<?php

namespace League\Omnipay\Common\Exception;

use League\Omnipay\Tests\TestCase;

class RuntimeExceptionTest extends TestCase
{
    public function testConstruct()
    {
        $exception = new RuntimeException('Oops');
        $this->assertSame('Oops', $exception->getMessage());
    }
}
