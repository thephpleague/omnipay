<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Mollie\Message;

use Omnipay\TestCase;

class PurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'partnerId' => 'my partner id',
                'amount' => '12.00',
                'issuer' => 'my bank',
                'returnUrl' => 'https://www.example.com/return',
                'notifyUrl' => 'https://www.example.com/return',
            )
        );
    }

    public function testGetData()
    {
        $this->request->initialize(array(
            'partnerId' => 'my partner id',
            'amount' => '12.00',
            'issuer' => 'my bank',
            'returnUrl' => 'https://www.example.com/return',
            'notifyUrl' => 'https://www.example.com/notify',
            'description' => 'a description',
            'testMode' => true,
        ));

        $data = $this->request->getData();

        $this->assertSame('fetch', $data['a']);
        $this->assertSame('my partner id', $data['partnerid']);
        $this->assertSame(1200, $data['amount']);
        $this->assertSame('my bank', $data['bank_id']);
        $this->assertSame('a description', $data['description']);
        $this->assertSame('https://www.example.com/return', $data['returnurl']);
        $this->assertSame('https://www.example.com/notify', $data['reporturl']);
        $this->assertSame('true', $data['testmode']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame('GET', $response->getRedirectMethod());
        $this->assertSame('https://www.mollie.nl/partners/ideal-test-bank?order_nr=M0361705M1078X9J&transaction_id=2e6455e7c1999436ef7093219f016fc5&trxid=0036170512173671', $response->getRedirectUrl());
        $this->assertNull($response->getRedirectData());
        $this->assertSame('2e6455e7c1999436ef7093219f016fc5', $response->getTransactionReference());
        $this->assertSame('Your iDEAL-payment has successfully been setup. Your customer should visit the given URL to make the payment', $response->getMessage());
        $this->assertNull($response->getCode());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('PurchaseFailure.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getRedirectUrl());
        $this->assertNull($response->getRedirectData());
        $this->assertSame("A fetch was issued without specification of 'partnerid'.", $response->getMessage());
        $this->assertSame('-2', $response->getCode());
    }
}
