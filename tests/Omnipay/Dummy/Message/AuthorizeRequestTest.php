<?php

namespace Omnipay\Dummy\Message;

use Omnipay\TestCase;

class AuthorizeRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'amount' => '10.00',
            'card' => $this->getValidCard(),
        ));
    }

    public function testGetData()
    {
        $data = $this->request->getData();
        $this->assertSame('10.00', $data['amount']);
    }
}
