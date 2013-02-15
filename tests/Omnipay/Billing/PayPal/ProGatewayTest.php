<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Billing\PayPal;

use Omnipay\GatewayTestCase;
use Omnipay\CreditCard;

class ProGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new ProGateway($this->httpClient, $this->httpRequest);

        $this->options = array(
            'amount' => 1000,
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

    /**
     * @expectedException \Omnipay\Exception\InvalidRequestException
     * @expectedExceptionMessage The amount parameter is required
     */
    public function testAuthorizeRequiresAmount()
    {
        $this->options['amount'] = 0;
        $response = $this->gateway->authorize($this->options);
    }

    public function testAuthorize()
    {
        $this->setMockResponse($this->httpClient, 'ProPurchaseSuccess.txt');

        $response = $this->gateway->authorize($this->options);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('7T274412RY6976239', $response->getGatewayReference());
    }

    public function testPurchase()
    {
        $this->setMockResponse($this->httpClient, 'ProPurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->options);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('7T274412RY6976239', $response->getGatewayReference());
    }
}
