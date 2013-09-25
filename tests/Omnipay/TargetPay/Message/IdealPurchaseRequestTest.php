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
        $this->request->initialize(array(
            'issuer' => '0001',
            'amount' => '100.00',
            'currency' => 'EUR',
            'description' => 'easy, no?',
            'language' => 'EN',
            'returnUrl' => 'http://localhost/return',
            'notifyUrl' => 'http://localhost/notify',
        ));
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('IdealPurchaseSuccess.txt');

        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('https://www.abnamro.nl/nl/ideal/identification.do?randomizedstring=4588770896&trxid=20000672693122', $response->getRedirectUrl());
        $this->assertEquals('0020000672693122', $response->getTransactionReference());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('IdealPurchaseFailure.txt');

        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('Internal error, failed to create transaction.', $response->getMessage());
        $this->assertEquals('TP9997', $response->getCode());
    }
}
