<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\GoCardless;

use Omnipay\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setAppId('abc');
        $this->gateway->setAppSecret('123');

        $this->options = array(
            'amount' => '10.00',
            'returnUrl' => 'https://www.example.com/return',
        );
    }

    public function testPurchase()
    {
        $response = $this->gateway->purchase($this->options)->send();

        $this->assertInstanceOf('\Omnipay\GoCardless\Message\PurchaseResponse', $response);
        $this->assertTrue($response->isRedirect());
        $this->assertStringStartsWith('https://gocardless.com/connect/bills/new?', $response->getRedirectUrl());
    }

    public function testCompletePurchaseSuccess()
    {
        $this->getHttpRequest()->request->replace(
            array(
                'resource_uri' => 'a',
                'resource_id' => 'b',
                'resource_type' => 'c',
                'signature' => '416f52e7d287dab49fa8445c1cd0957ca8ddf1c04a6300e00117dc0bedabc7d7',
            )
        );

        $this->setMockHttpResponse('CompletePurchaseSuccess.txt');

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('b', $response->getTransactionReference());
    }

    public function testCompletePurchaseError()
    {
        $this->getHttpRequest()->request->replace(
            array(
                'resource_uri' => 'a',
                'resource_id' => 'b',
                'resource_type' => 'c',
                'signature' => '416f52e7d287dab49fa8445c1cd0957ca8ddf1c04a6300e00117dc0bedabc7d7',
            )
        );

        $this->setMockHttpResponse('CompletePurchaseFailure.txt');

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('The resource cannot be confirmed', $response->getMessage());
    }

    /**
     * @expectedException Omnipay\Common\Exception\InvalidResponseException
     */
    public function testCompletePurchaseInvalid()
    {
        $this->getHttpRequest()->request->replace(
            array(
                'resource_uri' => 'a',
                'resource_id' => 'b',
                'resource_type' => 'c',
                'signature' => 'd',
            )
        );

        $response = $this->gateway->completePurchase($this->options)->send();
    }
}
