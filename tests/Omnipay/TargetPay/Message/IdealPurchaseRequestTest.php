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

class IdealPurchaseRequestTest extends TestCase
{
    /**
     * @var IdealPurchaseRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new IdealPurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testData()
    {
        $this->request->setIssuer('0001');
        $this->request->setAmount('100.00');
        $this->request->setDescription('desc');
        $this->request->setReturnUrl('http://localhost/return');

        $data = $this->request->getData();

        $this->assertArrayHasKey('rtlo', $data);
        $this->assertSame('0001', $data['bank']);
        $this->assertSame(10000, $data['amount']);
        $this->assertSame('desc', $data['description']);
        $this->assertArrayHasKey('language', $data);
        $this->assertArrayHasKey('currency', $data);
        $this->assertSame('http://localhost/return', $data['returnurl']);
        $this->assertArrayHasKey('reporturl', $data);
    }

    public function testEndpoint()
    {
        $this->assertSame('https://www.targetpay.com/ideal/start', $this->request->getEndpoint());
    }
}
