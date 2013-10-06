<?php

namespace Omnipay\TargetPay\Message;

use Omnipay\TestCase;

class MrcashCompletePurchaseRequestTest extends TestCase
{
    /**
     * @var MrcashCompletePurchaseRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new MrcashCompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testEndpoint()
    {
        $this->assertSame('https://www.targetpay.com/mrcash/check', $this->request->getEndpoint());
    }
}
