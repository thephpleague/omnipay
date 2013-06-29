<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Eway\Message;

use Omnipay\TestCase;

class RapidPurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new RapidPurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'apiKey' => 'my api key',
            'password' => 'secret',
            'amount' => '10.00',
            'returnUrl' => 'https://www.example.com/return',
        ));
    }

    public function testGetData()
    {
        $this->request->initialize(array(
            'apiKey' => 'my api key',
            'password' => 'secret',
            'amount' => '10.00',
            'transactionId' => '999',
            'description' => 'new car',
            'currency' => 'AUD',
            'clientIp' => '127.0.0.1',
            'returnUrl' => 'https://www.example.com/return',
            'card' => array(
                'firstName' => 'Patrick',
                'lastName' => 'Collison',
            ),
        ));

        $data = $this->request->getData();

        $this->assertSame('127.0.0.1', $data['CustomerIP']);
        $this->assertSame('https://www.example.com/return', $data['RedirectUrl']);
        $this->assertSame(1000, $data['Payment']['TotalAmount']);
        $this->assertSame('999', $data['Payment']['InvoiceNumber']);
        $this->assertSame('new car', $data['Payment']['InvoiceDescription']);
        $this->assertSame('AUD', $data['Payment']['CurrencyCode']);
        $this->assertSame('Patrick', $data['Customer']['FirstName']);
        $this->assertSame('Collison', $data['Customer']['LastName']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('RapidPurchaseRequestSuccess.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame('POST', $response->getRedirectMethod());
        $this->assertSame('https://secure-au.sandbox.ewaypayments.com/Process', $response->getRedirectUrl());
        $this->assertSame(array('EWAY_ACCESSCODE' => 'F9802j0-O7sdVLnOcb_3IPryTxHDtKY8u_0pb10GbYq-Xjvbc-5Bc_LhI-oBIrTxTCjhOFn7Mq-CwpkLDja5-iu-Dr3DjVTr9u4yxSB5BckdbJqSA4WWydzDO0jnPWfBdKcWL'), $response->getRedirectData());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getCode());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('RapidPurchaseRequestFailure.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getRedirectUrl());
        $this->assertNull($response->getRedirectData());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('V6011', $response->getMessage());
        $this->assertSame('V6011', $response->getCode());
    }
}
