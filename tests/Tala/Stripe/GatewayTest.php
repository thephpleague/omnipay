<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Stripe;

use Mockery as m;
use Tala\Request;

class GatewayTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->gateway = new Gateway();

        $this->browser = m::mock('\Buzz\Browser');
        $this->gateway->setBrowser($this->browser);
    }

    /**
     * @expectedException Tala\Exception
     * @expectedExceptionMessage Your card number is incorrect
     */
    public function testPurchaseError()
    {
        $request = new Request;
        $request->amount = 1000;

        $browserResponse = m::mock('Buzz\Message\Response');
        $browserResponse->shouldReceive('getContent')->once()
            ->andReturn('{"error":{"message":"Your card number is incorrect"}}');

        $this->browser->shouldReceive('post')->once()
            ->with('https://api.stripe.com/v1/charges', array(), m::type('string'))
            ->andReturn($browserResponse);

        $this->gateway->purchase($request, 'abc123');
    }

    public function testPurchaseSuccess()
    {
        $request = new Request();
        $request->amount = 1000;

        $browserResponse = m::mock('Buzz\Message\Response');
        $browserResponse->shouldReceive('getContent')->once()
            ->andReturn('{"id":"ch_12RgN9L7XhO9mI"}');

        $this->browser->shouldReceive('post')->once()
            ->with('https://api.stripe.com/v1/charges', array(), m::type('string'))
            ->andReturn($browserResponse);

        $response = $this->gateway->purchase($request, 'abc123');

        $this->assertEquals('ch_12RgN9L7XhO9mI', $response->getGatewayReference());
    }

    /**
     * @expectedException Tala\Exception
     * @expectedExceptionMessage Charge ch_12RgN9L7XhO9mI has already been refunded.
     */
    public function testRefundError()
    {
        $request = new Request();
        $request->amount = 1000;
        $request->gatewayReference = 'ch_12RgN9L7XhO9mI';

        $browserResponse = m::mock('Buzz\Message\Response');
        $browserResponse->shouldReceive('getContent')->once()
            ->andReturn('{"error":{"message":"Charge ch_12RgN9L7XhO9mI has already been refunded."}}');

        $this->browser->shouldReceive('post')->once()
            ->with('https://api.stripe.com/v1/charges/ch_12RgN9L7XhO9mI/refund', array(), m::type('string'))
            ->andReturn($browserResponse);

        $this->gateway->refund($request);
    }

    public function testRefundSuccess()
    {
        $request = new Request();
        $request->amount = 1000;
        $request->gatewayReference = 'ch_12RgN9L7XhO9mI';

        $browserResponse = m::mock('Buzz\Message\Response');
        $browserResponse->shouldReceive('getContent')->once()
            ->andReturn('{"id":"ch_12RgN9L7XhO9mI"}');

        $this->browser->shouldReceive('post')->once()
            ->with('https://api.stripe.com/v1/charges/ch_12RgN9L7XhO9mI/refund', array(), m::type('string'))
            ->andReturn($browserResponse);

        $response = $this->gateway->refund($request);

        $this->assertEquals('ch_12RgN9L7XhO9mI', $response->getGatewayReference());
    }
}
