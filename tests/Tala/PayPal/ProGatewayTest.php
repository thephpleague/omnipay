<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\PayPal;

use Mockery as m;
use Tala\CreditCard;
use Tala\Request;

class ProGatewayTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->httpClient = m::mock('\Tala\HttpClient\HttpClientInterface');
        $this->httpRequest = m::mock('\Symfony\Component\HttpFoundation\Request');

        $this->gateway = new ProGateway(array(
            'httpClient' => $this->httpClient,
            'httpRequest' => $this->httpRequest,
        ));

        $this->card = new CreditCard(array(
            'firstName' => 'Example',
            'lastName' => 'User',
            'number' => '4111111111111111',
            'expiryMonth' => '12',
            'expiryYear' => '2016',
            'cvv' => '123',
        ));

        $this->request = new Request();
        $this->request->amount = 1000;
    }

    public function testAuthorizeRequiresAmount()
    {
        $this->setExpectedException('\Tala\Exception\MissingParameterException', 'The amount parameter is required');

        $this->request->amount = 0;
        $response = $this->gateway->authorize($this->request, $this->card);
    }

    public function testAuthorize()
    {
        $this->httpClient->shouldReceive('get')
            ->with(m::type('string'))->once()
            ->andReturn('TIMESTAMP=2012%2d09%2d06T06%3a34%3a46Z&CORRELATIONID=1a0e1b3ba661b&ACK=Success&VERSION=85%2e0&BUILD=3587318&AMT=11%2e00&CURRENCYCODE=USD&AVSCODE=X&CVV2MATCH=M&TRANSACTIONID=7T274412RY6976239');

        $response = $this->gateway->authorize($this->request, $this->card);

        $this->assertInstanceOf('\Tala\Response', $response);
        $this->assertEquals('7T274412RY6976239', $response->getGatewayReference());
    }

    public function testPurchase()
    {
        $this->httpClient->shouldReceive('get')
            ->with(m::type('string'))->once()
            ->andReturn('TIMESTAMP=2012%2d09%2d06T06%3a34%3a46Z&CORRELATIONID=1a0e1b3ba661b&ACK=Success&VERSION=85%2e0&BUILD=3587318&AMT=11%2e00&CURRENCYCODE=USD&AVSCODE=X&CVV2MATCH=M&TRANSACTIONID=7T274412RY6976239');

        $response = $this->gateway->purchase($this->request, $this->card);

        $this->assertInstanceOf('\Tala\Response', $response);
        $this->assertEquals('7T274412RY6976239', $response->getGatewayReference());
    }
}
