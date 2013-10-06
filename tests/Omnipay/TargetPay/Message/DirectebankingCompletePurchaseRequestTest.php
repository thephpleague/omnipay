<?php

namespace Omnipay\TargetPay\Message;

use Omnipay\TestCase;

class DirectebankingCompletePurchaseRequestTest extends TestCase
{
    /**
     * @var DirectebankingCompletePurchaseRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new DirectebankingCompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testEndpoint()
    {
        $this->assertSame('https://www.targetpay.com/directebanking/check', $this->request->getEndpoint());
    }
}
