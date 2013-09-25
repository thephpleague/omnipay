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

use Mockery as m;
use Omnipay\TestCase;

class PurchaseRequestTest extends TestCase
{
    /**
     * @var PurchaseRequest
     */
    private $request;

    protected function setUp()
    {
        $arguments = array($this->getHttpClient(), $this->getHttpRequest());
        $this->request = m::mock('Omnipay\TargetPay\Message\PurchaseRequest[getData,getEndpoint]', $arguments);
        $this->request->shouldReceive('getData')->andReturn(array());
        $this->request->shouldReceive('getEndpoint')->andReturn('http://localhost');
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('https://www.targetpay.com/mrcash/start.php?trxid=15983095', $response->getRedirectUrl());
        $this->assertEquals('15983095', $response->getTransactionReference());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('PurchaseFailure.txt');

        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('Account disabled.', $response->getMessage());
        $this->assertEquals('TP0016', $response->getCode());
    }
}
