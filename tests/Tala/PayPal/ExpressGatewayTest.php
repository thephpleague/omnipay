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

class ExpressGatewayTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->httpClient = m::mock('\Tala\HttpClient\HttpClientInterface');
        $this->httpRequest = m::mock('\Symfony\Component\HttpFoundation\Request');

        $this->gateway = new ExpressGateway(array(
            'httpClient' => $this->httpClient,
            'httpRequest' => $this->httpRequest,
        ));

        $this->card = new CreditCard;

        $this->request = new Request;
        $this->request->amount = 1000;
        $this->request->cancelUrl = 'https://www.example.com/checkout';
        $this->request->returnUrl = 'https://www.example.com/complete';
    }

    public function testAuthorize()
    {
        $this->httpClient->shouldReceive('get')
            ->with(m::type('string'))->once()
            ->andReturn('TOKEN=EC%2d5BV04722RH241693H&TIMESTAMP=2013%2d01%2d11T18%3a50%3a23Z&CORRELATIONID=43cb1f2bec8db&ACK=Success&VERSION=85%2e0&BUILD=4181146');

        $response = $this->gateway->authorize($this->request, $this->card);

        $this->assertInstanceOf('\Tala\RedirectResponse', $response);
        $this->assertEquals('https://www.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token=EC-5BV04722RH241693H', $response->getRedirectUrl());
    }

    public function testPurchase()
    {
        $this->httpClient->shouldReceive('get')
            ->with(m::type('string'))->once()
            ->andReturn('TOKEN=EC%2d5BV04722RH241693H&TIMESTAMP=2013%2d01%2d11T18%3a50%3a23Z&CORRELATIONID=43cb1f2bec8db&ACK=Success&VERSION=85%2e0&BUILD=4181146');

        $response = $this->gateway->purchase($this->request, $this->card);

        $this->assertInstanceOf('\Tala\RedirectResponse', $response);
        $this->assertEquals('https://www.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token=EC-5BV04722RH241693H', $response->getRedirectUrl());
    }
}
