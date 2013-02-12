<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Billing\Pin;

use Mockery as m;
use Omnipay\BaseGatewayTest;
use Omnipay\CreditCard;
use Omnipay\Request;

class GatewayTest extends BaseGatewayTest
{
    public function setUp()
    {
        $this->httpClient = m::mock('\Omnipay\HttpClient\HttpClientInterface');
        $this->httpRequest = m::mock('\Symfony\Component\HttpFoundation\Request');

        $this->gateway = new Gateway($this->httpClient, $this->httpRequest);

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

    public function testPurchaseError()
    {
        $this->httpClient->shouldReceive('post')->once()
            ->with('https://api.pin.net.au/1/charges', m::type('array'), m::type('array'))
            ->andReturn('{"error":"standard_error_name","error_description":"A description of the error."}');

        $response = $this->gateway->purchase($this->options);

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('A description of the error.', $response->getMessage());
    }

    public function testPurchaseSuccess()
    {
        $this->httpClient->shouldReceive('post')->once()
            ->with('https://api.pin.net.au/1/charges', m::type('array'), m::type('array'))
            ->andReturn('{"response":{"token":"ch_lfUYEBK14zotCTykezJkfg","status_message":"Success!"}}');

        $response = $this->gateway->purchase($this->options);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('ch_lfUYEBK14zotCTykezJkfg', $response->getGatewayReference());
    }
}
