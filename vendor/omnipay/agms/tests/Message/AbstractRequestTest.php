<?php

namespace Omnipay\Agms\Message;

use Mockery as mockery;
use Omnipay\Tests\TestCase;

class AuthorizeRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = mockery::mock('Omnipay\Agms\Message\AbstractRequest')->makePartial();
        $this->request->initialize(array(
            'amount' => '10.00',
        ));
    }

    public function testEndpoint()
    {
        $this->assertSame('https://gateway.agms.com/roxapi/agms.asmx', $this->request->getEndpoint());
    }
    
    public function testGetData()
    {
        $data = $this->request->getAmount();
        $this->assertSame('10.00', $data);
    }
}
