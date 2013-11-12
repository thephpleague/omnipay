<?php

namespace Omnipay\Common;

use Mockery as m;
use Omnipay\Tests\TestCase;

class AbstractGatewayTest extends TestCase
{
    public function setUp()
    {
        $this->gateway = m::mock("\Omnipay\Common\AbstractGateway[getName,getDefaultParameters,purchase]");
    }

    public function testGetShortName()
    {
        $this->assertSame('\\'.get_class($this->gateway), $this->gateway->getShortName());
    }
}
