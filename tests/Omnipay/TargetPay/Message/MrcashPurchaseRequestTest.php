<?php

namespace Omnipay\TargetPay\Message;

use Omnipay\TestCase;

class MrcashPurchaseRequestTest extends TestCase
{
    /**
     * @var MrcashPurchaseRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new MrcashPurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testData()
    {
        $this->request->setAmount('100.00');
        $this->request->setDescription('desc');
        $this->request->setClientIp('127.0.0.1');
        $this->request->setReturnUrl('http://localhost/return');

        $data = $this->request->getData();

        $this->assertArrayHasKey('rtlo', $data);
        $this->assertSame(10000, $data['amount']);
        $this->assertSame('desc', $data['description']);
        $this->assertArrayHasKey('lang', $data);
        $this->assertSame('127.0.0.1', $data['userip']);
        $this->assertSame('http://localhost/return', $data['returnurl']);
        $this->assertArrayHasKey('reporturl', $data);
    }

    public function testEndpoint()
    {
        $this->assertSame('https://www.targetpay.com/mrcash/start', $this->request->getEndpoint());
    }
}
