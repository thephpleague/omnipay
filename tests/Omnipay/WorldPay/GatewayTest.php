<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\WorldPay;

use Omnipay\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->httpClient, $this->httpRequest);
        $this->gateway->setCallbackPassword('bar123');

        $this->options = array(
            'amount' => 1000,
            'returnUrl' => 'https://www.example.com/return',
        );
    }

    public function testPurchase()
    {
        $response = $this->gateway->purchase($this->options);

        $this->assertInstanceOf('\Omnipay\Common\RedirectResponse', $response);
        $this->assertTrue($response->isRedirect());
        $this->assertContains('https://secure.worldpay.com/wcc/purchase?', $response->getRedirectUrl());
    }

    public function testCompletePurchaseSuccess()
    {
        $this->httpRequest->request->replace(
            array(
                'callbackPW' => 'bar123',
                'transStatus' => 'Y',
                'transId' => 'abc123',
                'rawAuthMessage' => '',
            )
        );

        $response = $this->gateway->completePurchase($this->options);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('abc123', $response->getGatewayReference());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidResponseException
     */
    public function testCompletePurchaseInvalidCallbackPassword()
    {
        $this->httpRequest->request->replace(
            array(
                'callbackPW' => 'fake',
            )
        );

        $response = $this->gateway->completePurchase($this->options);
    }

    public function testCompletePurchaseError()
    {
        $this->httpRequest->request->replace(
            array(
                'callbackPW' => 'bar123',
                'transStatus' => 'N',
                'transId' => '',
                'rawAuthMessage' => 'Declined',
            )
        );

        $response = $this->gateway->completePurchase($this->options);

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Declined', $response->getMessage());
    }
}
