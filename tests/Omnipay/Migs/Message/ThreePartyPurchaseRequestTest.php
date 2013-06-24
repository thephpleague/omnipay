<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Migs\Message;

use Omnipay\TestCase;

class ThreePartyPurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new ThreePartyPurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testSignature()
    {
        $this->request->initialize(
            array(
                'amount'             => '12.00',
                'transactionId'      => 123,
                'returnUrl'          => 'https://www.example.com/return',

                'merchantId'         => '123',
                'merchantAccessCode' => '123',
                'secureHash'         => '123',
            )
        );

        $data = $this->request->getData();

        $this->assertSame('FC86354CC09D414EF308A6FA8CE4F9BB', $data['vpc_SecureHash']);
    }

    public function testPurchase()
    {
        $this->request->initialize(
            array(
                'amount'             => '12.00',
                'transactionId'      => 123,
                'returnUrl'          => 'https://www.example.com/return',

                'merchantId'         => '123',
                'merchantAccessCode' => '123',
                'secureHash'         => '123',
            )
        );

        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\Migs\Message\ThreePartyPurchaseResponse', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getCode());

        $this->assertStringStartsWith('https://migs.mastercard.com.au/vpcpay?', $response->getRedirectUrl());
        $this->assertSame('GET', $response->getRedirectMethod());
        $this->assertArrayHasKey('vpc_SecureHash', $response->getData());
    }
}
