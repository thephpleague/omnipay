<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\CardSave;

use Omnipay\Common\CreditCard;
use Omnipay\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->httpClient, $this->httpRequest);

        $this->options = array(
            'amount' => 1000,
            'returnUrl' => 'https://www.example.com/return',
            'card' => new CreditCard(array(
                'firstName' => 'Example',
                'lastName' => 'User',
                'number' => '4111111111111111',
                'expiryMonth' => '12',
                'expiryYear' => '2016',
                'cvv' => '123',
            )),
        );
    }

    public function testPurchase()
    {
        $this->setMockResponse($this->httpClient, 'PurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->options);

        $this->assertInstanceOf('\Omnipay\CardSave\Response', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('130215141054377801316798', $response->getGatewayReference());
    }

    public function testPurchaseError()
    {
        $this->setMockResponse($this->httpClient, 'PurchaseFailure.txt');

        $response = $this->gateway->purchase($this->options);

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Input variable errors', $response->getMessage());
    }
}
