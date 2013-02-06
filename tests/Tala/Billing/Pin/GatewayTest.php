<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\Pin;

use Mockery as m;
use Tala\CreditCard;
use Tala\Request;

class GatewayTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->httpClient = m::mock('\Tala\HttpClient\HttpClientInterface');
        $this->httpRequest = m::mock('\Symfony\Component\HttpFoundation\Request');

        $this->gateway = new Gateway($this->httpClient, $this->httpRequest);

        $this->request = new Request;
        $this->request->amount = 1000;
    }

    /**
     * @expectedException Tala\Exception
     * @expectedExceptionMessage A description of the error.
     */
    public function testPurchaseError()
    {
        $this->httpRequest->shouldReceive('getClientIp')->once()->andReturn('127.0.0.1');

        $this->httpClient->shouldReceive('post')->once()
            ->with('https://api.pin.net.au/1/charges', m::type('array'), m::type('array'))
            ->andReturn('{"error":"standard_error_name","error_description":"A description of the error."}');

        $this->gateway->purchase($this->request, 'abc123');
    }

    public function testPurchaseSuccess()
    {
        $this->httpRequest->shouldReceive('getClientIp')->once()->andReturn('127.0.0.1');

        $this->httpClient->shouldReceive('post')->once()
            ->with('https://api.pin.net.au/1/charges', m::type('array'), m::type('array'))
            ->andReturn('{"response":{"token":"ch_lfUYEBK14zotCTykezJkfg","status_message":"Success!"}}');

        $response = $this->gateway->purchase($this->request, 'abc123');

        $this->assertEquals('ch_lfUYEBK14zotCTykezJkfg', $response->getGatewayReference());
    }
}
