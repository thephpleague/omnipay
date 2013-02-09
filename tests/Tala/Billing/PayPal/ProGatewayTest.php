<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\PayPal;

use Mockery as m;
use Tala\BaseGatewayTest;
use Tala\CreditCard;
use Tala\Request;

class ProGatewayTest extends BaseGatewayTest
{
    public function setUp()
    {
        $this->httpClient = m::mock('\Tala\HttpClient\HttpClientInterface');
        $this->httpRequest = m::mock('\Symfony\Component\HttpFoundation\Request');

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
     * @expectedException \Tala\Exception\InvalidRequestException
     * @expectedExceptionMessage The amount parameter is required
     */
    public function testAuthorizeRequiresAmount()
    {
        $this->options['amount'] = 0;
        $response = $this->gateway->authorize($this->options);
    }

    public function testAuthorize()
    {
        $this->httpClient->shouldReceive('get')
            ->with(m::type('string'))->once()
            ->andReturn('TIMESTAMP=2012%2d09%2d06T06%3a34%3a46Z&CORRELATIONID=1a0e1b3ba661b&ACK=Success&VERSION=85%2e0&BUILD=3587318&AMT=11%2e00&CURRENCYCODE=USD&AVSCODE=X&CVV2MATCH=M&TRANSACTIONID=7T274412RY6976239');

        $response = $this->gateway->authorize($this->options);

        $this->assertInstanceOf('\Tala\Response', $response);
        $this->assertEquals('7T274412RY6976239', $response->getGatewayReference());
    }

    public function testPurchase()
    {
        $this->httpClient->shouldReceive('get')
            ->with(m::type('string'))->once()
            ->andReturn('TIMESTAMP=2012%2d09%2d06T06%3a34%3a46Z&CORRELATIONID=1a0e1b3ba661b&ACK=Success&VERSION=85%2e0&BUILD=3587318&AMT=11%2e00&CURRENCYCODE=USD&AVSCODE=X&CVV2MATCH=M&TRANSACTIONID=7T274412RY6976239');

        $response = $this->gateway->purchase($this->options);

        $this->assertInstanceOf('\Tala\Response', $response);
        $this->assertEquals('7T274412RY6976239', $response->getGatewayReference());
    }
}
