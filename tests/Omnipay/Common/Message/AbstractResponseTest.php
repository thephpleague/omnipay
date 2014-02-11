<?php

namespace Omnipay\Common\Message;

use Mockery as m;
use Omnipay\Tests\TestCase;

class AbstractResponseTest extends TestCase
{
    public function testDefaultMethods()
    {
        $response = m::mock('\Omnipay\Common\Message\AbstractResponse')->makePartial();

        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getData());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getCode());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\RuntimeException
     */
    public function testCannotRedirectResponseThatIsNotRedirectResponseInterface()
    {
        $response = m::mock('\Omnipay\Common\Message\AbstractResponse')->makePartial();

        $response->getRedirectResponse();
    }
}
