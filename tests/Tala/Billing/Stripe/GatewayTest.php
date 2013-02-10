<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\Stripe;

use Mockery as m;
use Tala\BaseGatewayTest;
use Tala\Request;

class GatewayTest extends BaseGatewayTest
{
    public function setUp()
    {
        $this->httpClient = m::mock('\Tala\HttpClient\HttpClientInterface');
        $this->httpRequest = m::mock('\Symfony\Component\HttpFoundation\Request');

        $this->gateway = new Gateway($this->httpClient, $this->httpRequest);
        $this->gateway->setApiKey('abc123');

        $this->options = array(
            'amount' => 1000,
        );
    }

    public function testPurchaseError()
    {
        $this->httpClient->shouldReceive('post')->once()
            ->with('https://api.stripe.com/v1/charges', m::type('array'), array('Authorization: Basic YWJjMTIzOg=='))
            ->andReturn('{"error":{"message":"Your card number is incorrect"}}');

        $response = $this->gateway->purchase($this->options);

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Your card number is incorrect', $response->getMessage());
    }

    public function testPurchaseSuccess()
    {
        $this->httpClient->shouldReceive('post')->once()
            ->with('https://api.stripe.com/v1/charges', m::type('array'), array('Authorization: Basic YWJjMTIzOg=='))
            ->andReturn('{"id":"ch_12RgN9L7XhO9mI"}');

        $response = $this->gateway->purchase($this->options);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('ch_12RgN9L7XhO9mI', $response->getGatewayReference());
    }

    public function testRefundError()
    {
        $this->httpClient->shouldReceive('post')->once()
            ->with('https://api.stripe.com/v1/charges/ch_12RgN9L7XhO9mI/refund', m::type('array'), array('Authorization: Basic YWJjMTIzOg=='))
            ->andReturn('{"error":{"message":"Charge ch_12RgN9L7XhO9mI has already been refunded."}}');

        $response = $this->gateway->refund(array('amount' => 1000, 'gatewayReference' => 'ch_12RgN9L7XhO9mI'));

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Charge ch_12RgN9L7XhO9mI has already been refunded.', $response->getMessage());
    }

    public function testRefundSuccess()
    {
        $this->httpClient->shouldReceive('post')->once()
            ->with('https://api.stripe.com/v1/charges/ch_12RgN9L7XhO9mI/refund', m::type('array'), array('Authorization: Basic YWJjMTIzOg=='))
            ->andReturn('{"id":"ch_12RgN9L7XhO9mI"}');

        $response = $this->gateway->refund(array('amount' => 1000, 'gatewayReference' => 'ch_12RgN9L7XhO9mI'));

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('ch_12RgN9L7XhO9mI', $response->getGatewayReference());
    }
}
