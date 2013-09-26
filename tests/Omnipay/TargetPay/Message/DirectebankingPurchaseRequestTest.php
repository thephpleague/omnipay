<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\TargetPay\Message;

use Omnipay\TestCase;

class DirectebankingPurchaseRequestTest extends TestCase
{
    /**
     * @var DirectebankingPurchaseRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new DirectebankingPurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testData()
    {
        $this->request->setAmount('100.00');
        $this->request->setDescription('desc');
        $this->request->setCountry('01');
        $this->request->setServiceType('1');
        $this->request->setClientIp('127.0.0.1');
        $this->request->setReturnUrl('http://localhost/return');

        $data = $this->request->getData();

        $this->assertArrayHasKey('rtlo', $data);
        $this->assertSame('desc', $data['description']);
        $this->assertSame(10000, $data['amount']);
        $this->assertSame('01', $data['country']);
        $this->assertArrayHasKey('lang', $data);
        $this->assertSame('1', $data['type']);
        $this->assertSame('127.0.0.1', $data['userip']);
        $this->assertSame('http://localhost/return', $data['returnurl']);
        $this->assertArrayHasKey('reporturl', $data);
    }

    public function testEndpoint()
    {
        $this->assertSame('https://www.targetpay.com/directebanking/start', $this->request->getEndpoint());
    }
}
