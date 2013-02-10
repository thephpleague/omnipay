<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\GoCardless;

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
        $this->gateway->setAppId('abc');
        $this->gateway->setAppSecret('123');

        $this->options = array(
            'amount' => 1000,
            'returnUrl' => 'https://www.example.com/return',
        );
    }

    public function testPurchase()
    {
        $response = $this->gateway->purchase($this->options);

        $this->assertInstanceOf('\Tala\RedirectResponse', $response);
        $this->assertTrue($response->isRedirect());
        $this->assertStringStartsWith('https://gocardless.com/connect/bills/new?', $response->getRedirectUrl());
    }

    public function testCompletePurchaseSuccess()
    {
        $this->httpRequest->shouldReceive('get')->with('resource_uri')->once()->andReturn('a');
        $this->httpRequest->shouldReceive('get')->with('resource_id')->once()->andReturn('b');
        $this->httpRequest->shouldReceive('get')->with('resource_type')->once()->andReturn('c');
        $this->httpRequest->shouldReceive('get')->with('signature')->once()
            ->andReturn('416f52e7d287dab49fa8445c1cd0957ca8ddf1c04a6300e00117dc0bedabc7d7');

        $this->httpClient->shouldReceive('post')
            ->with('https://gocardless.com/api/v1/confirm', m::type('string'), m::type('array'))->once()
            ->andReturn('{"success":true}');

        $response = $this->gateway->completePurchase($this->options);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('b', $response->getGatewayReference());
    }

    public function testCompletePurchaseError()
    {
        $this->httpRequest->shouldReceive('get')->with('resource_uri')->once()->andReturn('a');
        $this->httpRequest->shouldReceive('get')->with('resource_id')->once()->andReturn('b');
        $this->httpRequest->shouldReceive('get')->with('resource_type')->once()->andReturn('c');
        $this->httpRequest->shouldReceive('get')->with('signature')->once()
            ->andReturn('416f52e7d287dab49fa8445c1cd0957ca8ddf1c04a6300e00117dc0bedabc7d7');

        $this->httpClient->shouldReceive('post')
            ->with('https://gocardless.com/api/v1/confirm', m::type('string'), m::type('array'))->once()
            ->andReturn('{"error":["The resource cannot be confirmed"]}');

        $response = $this->gateway->completePurchase($this->options);

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('The resource cannot be confirmed', $response->getMessage());
    }

    /**
     * @expectedException Tala\Exception\InvalidResponseException
     */
    public function testCompletePurchaseInvalid()
    {
        $this->httpRequest->shouldReceive('get')->with('resource_uri')->once()->andReturn('a');
        $this->httpRequest->shouldReceive('get')->with('resource_id')->once()->andReturn('b');
        $this->httpRequest->shouldReceive('get')->with('resource_type')->once()->andReturn('c');
        $this->httpRequest->shouldReceive('get')->with('signature')->once()->andReturn('d');

        $this->gateway->completePurchase($this->options);
    }
}
