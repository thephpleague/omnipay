<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Billing\Dummy;

use Mockery as m;
use Omnipay\CreditCard;
use Omnipay\BaseGatewayTest;
use Omnipay\Request;

class GatewayTest extends BaseGatewayTest
{
    public function setUp()
    {
        $this->httpClient = m::mock('\Omnipay\HttpClient\HttpClientInterface');
        $this->httpRequest = m::mock('\Symfony\Component\HttpFoundation\Request');

        $this->gateway = new Gateway($this->httpClient, $this->httpRequest);

        $card = new CreditCard(array(
            'firstName' => 'Example',
            'lastName' => 'User',
            'number' => '4111111111111111',
            'expiryMonth' => '12',
            'expiryYear' => '2016',
            'cvv' => '123',
        ));

        $this->options = array('amount' => 1000, 'card' => $card);
    }

    public function testAuthorize()
    {
        $response = $this->gateway->authorize($this->options);

        $this->assertInstanceOf('\Omnipay\Billing\Dummy\Response', $response);
        $this->assertTrue($response->isSuccessful());
    }

    public function testPurchase()
    {
        $response = $this->gateway->purchase($this->options);

        $this->assertInstanceOf('\Omnipay\Billing\Dummy\Response', $response);
        $this->assertTrue($response->isSuccessful());
    }
}
