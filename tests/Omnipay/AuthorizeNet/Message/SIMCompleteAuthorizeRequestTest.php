<?php

namespace Omnipay\AuthorizeNet\Message;

use Omnipay\TestCase;

class SIMCompleteAuthorizeRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new SIMCompleteAuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testGetHash()
    {
        $this->assertSame(md5(''), $this->request->getHash());

        $this->request->setHashSecret('hashsec');
        $this->request->setApiLoginId('apilogin');
        $this->request->setTransactionId('trnid');
        $this->request->setAmount('10.00');

        $this->assertSame(md5('hashsecapilogintrnid10.00'), $this->request->getHash());
    }
}
