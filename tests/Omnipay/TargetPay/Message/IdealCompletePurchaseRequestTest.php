<?php

namespace Omnipay\TargetPay\Message;

use Omnipay\TestCase;

class IdealCompletePurchaseRequestTest extends TestCase
{
    /**
     * @var IdealCompletePurchaseRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new IdealCompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testEndpoint()
    {
        $this->assertSame('https://www.targetpay.com/ideal/check', $this->request->getEndpoint());
    }
}
