<?php

namespace Omnipay\Common\Exception;

use Omnipay\Tests\TestCase;

class RuntimeExceptionTest extends TestCase
{
    public function testConstruct()
    {
        $exception = new RuntimeException('Oops');
        $this->assertSame('Oops', $exception->getMessage());
    }
}
