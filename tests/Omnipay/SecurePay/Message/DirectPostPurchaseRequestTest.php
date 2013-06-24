<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\SecurePay\Message;

use Omnipay\TestCase;

class DirectPostPurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new DirectPostPurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->request->initialize(
            array(
                'merchantId' => 'foo',
                'transactionPassword' => 'bar',
                'amount' => '12.00',
                'returnUrl' => 'https://www.example.com/return',
            )
        );
    }

    public function testFingerprint()
    {
        // force timestamp for testing
        $data = $this->request->getData();
        $data['EPS_TIMESTAMP'] = '20130416123332';

        $this->assertSame('652856e75b04c5916a41082e04c9390961497f65', $this->request->generateFingerprint($data));
    }

    public function testSend()
    {
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\SecurePay\Message\DirectPostAuthorizeResponse', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getCode());

        $this->assertSame('https://api.securepay.com.au/live/directpost/authorise', $response->getRedirectUrl());
        $this->assertSame('POST', $response->getRedirectMethod());

        $data = $response->getData();
        $this->assertArrayHasKey('EPS_FINGERPRINT', $data);
        $this->assertSame('0', $data['EPS_TXNTYPE']);
    }
}
