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

        $this->options = array(
            'amount' => 1000,
        );
    }

    /**
     * @expectedException Tala\Exception
     * @expectedExceptionMessage Your card number is incorrect
     */
    public function testPurchaseError()
    {
        $this->httpClient->shouldReceive('post')->once()
            ->with('https://api.stripe.com/v1/charges', m::type('array'))
            ->andReturn('{"error":{"message":"Your card number is incorrect"}}');

        $this->gateway->purchase($this->options);
    }

    public function testPurchaseSuccess()
    {
        $this->httpClient->shouldReceive('post')->once()
            ->with('https://api.stripe.com/v1/charges', m::type('array'))
            ->andReturn('{"id":"ch_12RgN9L7XhO9mI"}');

        $response = $this->gateway->purchase($this->options);

        $this->assertEquals('ch_12RgN9L7XhO9mI', $response->getGatewayReference());
    }

    /**
     * @expectedException Tala\Exception
     * @expectedExceptionMessage Charge ch_12RgN9L7XhO9mI has already been refunded.
     */
    public function testRefundError()
    {
        $this->httpClient->shouldReceive('post')->once()
            ->with('https://api.stripe.com/v1/charges/ch_12RgN9L7XhO9mI/refund', m::type('array'))
            ->andReturn('{"error":{"message":"Charge ch_12RgN9L7XhO9mI has already been refunded."}}');

        $this->gateway->refund(array('amount' => 1000, 'gatewayReference' => 'ch_12RgN9L7XhO9mI'));
    }

    public function testRefundSuccess()
    {
        $this->httpClient->shouldReceive('post')->once()
            ->with('https://api.stripe.com/v1/charges/ch_12RgN9L7XhO9mI/refund', m::type('array'))
            ->andReturn('{"id":"ch_12RgN9L7XhO9mI"}');

        $response = $this->gateway->refund(array('amount' => 1000, 'gatewayReference' => 'ch_12RgN9L7XhO9mI'));

        $this->assertEquals('ch_12RgN9L7XhO9mI', $response->getGatewayReference());
    }
}
