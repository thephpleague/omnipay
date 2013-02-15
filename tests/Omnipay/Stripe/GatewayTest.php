<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Stripe;

use Omnipay\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->httpClient, $this->httpRequest);
        $this->gateway->setApiKey('abc123');

        $this->options = array(
            'amount' => 1000,
        );
    }

    public function testPurchaseError()
    {
        $this->setMockResponse($this->httpClient, 'PurchaseFailure.txt');

        $response = $this->gateway->purchase($this->options);

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Your card was declined', $response->getMessage());
        $this->assertSame('ch_1IUAZQWFYrPooM', $response->getGatewayReference());
    }

    public function testPurchaseSuccess()
    {
        $this->setMockResponse($this->httpClient, 'PurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->options);

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('ch_1IU9gcUiNASROd', $response->getGatewayReference());
        $this->assertNull($response->getMessage());
    }

    public function testRefundError()
    {
        $this->setMockResponse($this->httpClient, 'RefundFailure.txt');

        $response = $this->gateway->refund(array('amount' => 1000, 'gatewayReference' => 'ch_12RgN9L7XhO9mI'));

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Charge ch_12RgN9L7XhO9mI has already been refunded.', $response->getMessage());
    }

    public function testRefundSuccess()
    {
        $this->setMockResponse($this->httpClient, 'RefundSuccess.txt');

        $response = $this->gateway->refund(array('amount' => 1000, 'gatewayReference' => 'ch_12RgN9L7XhO9mI'));

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('ch_12RgN9L7XhO9mI', $response->getGatewayReference());
    }
}
