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

use Mockery as m;
use Omnipay\BaseGatewayTest;
use Omnipay\Request;

class ExpressGatewayTest extends BaseGatewayTest
{
    public function setUp()
    {
        $this->httpClient = m::mock('\Omnipay\HttpClient\HttpClientInterface');
        $this->httpRequest = m::mock('\Symfony\Component\HttpFoundation\Request');

        $this->gateway = new ExpressGateway($this->httpClient, $this->httpRequest);

        $this->options = array(
            'amount' => 1000,
            'returnUrl' => 'https://www.example.com/return',
            'cancelUrl' => 'https://www.example.com/cancel',
        );
    }

    public function testAuthorize()
    {
        $this->httpClient->shouldReceive('get')
            ->with(m::type('string'))->once()
            ->andReturn('TOKEN=EC%2d5BV04722RH241693H&TIMESTAMP=2013%2d01%2d11T18%3a50%3a23Z&CORRELATIONID=43cb1f2bec8db&ACK=Success&VERSION=85%2e0&BUILD=4181146');

        $response = $this->gateway->authorize($this->options);

        $this->assertInstanceOf('\Omnipay\RedirectResponse', $response);
        $this->assertEquals('https://www.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token=EC-5BV04722RH241693H', $response->getRedirectUrl());
    }

    public function testPurchase()
    {
        $this->httpClient->shouldReceive('get')
            ->with(m::type('string'))->once()
            ->andReturn('TOKEN=EC%2d5BV04722RH241693H&TIMESTAMP=2013%2d01%2d11T18%3a50%3a23Z&CORRELATIONID=43cb1f2bec8db&ACK=Success&VERSION=85%2e0&BUILD=4181146');

        $response = $this->gateway->purchase($this->options);

        $this->assertInstanceOf('\Omnipay\RedirectResponse', $response);
        $this->assertEquals('https://www.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token=EC-5BV04722RH241693H', $response->getRedirectUrl());
    }
}
