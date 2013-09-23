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

class MrcashPurchaseRequestTest extends TestCase
{
    /**
     * @var PurchaseRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new MrcashPurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'amount' => '100.00',
            'description' => 'easy, no?',
            'clientIp' => '127.0.0.1',
            'language' => 'EN',
            'returnUrl' => 'http://localhost/return',
            'notifyUrl' => 'http://localhost/notify',
        ));
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('MrcashPurchaseSuccess.txt');

        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('https://www.targetpay.com/mrcash/start.php?trxid=15983095', $response->getRedirectUrl());
        $this->assertEquals('15983095', $response->getTransactionReference());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('MrcashPurchaseFailure.txt');

        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('Account disabled.', $response->getMessage());
        $this->assertEquals('TP0016', $response->getCode());
    }
}
