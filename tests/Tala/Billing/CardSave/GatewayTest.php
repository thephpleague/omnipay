<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\CardSave;

use Mockery as m;
use Tala\CreditCard;
use Tala\Request;

class GatewayTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->httpClient = m::mock('\Tala\HttpClient\HttpClientInterface');
        $this->httpRequest = m::mock('\Symfony\Component\HttpFoundation\Request');

        $this->gateway = new Gateway(array(
            'httpClient' => $this->httpClient,
            'httpRequest' => $this->httpRequest,
        ));

        $this->request = new Request;
        $this->request->amount = 1000;
        $this->request->returnUrl = 'https://www.example.com/complete';

        $this->card = new CreditCard(array(
            'firstName' => 'Example',
            'lastName' => 'User',
            'number' => '4111111111111111',
            'expiryMonth' => '12',
            'expiryYear' => '2016',
            'cvv' => '123',
        ));
    }

    public function testPurchase()
    {
        $this->httpRequest->shouldReceive('getClientIp')->once()->andReturn('127.0.0.1');

        $this->httpClient->shouldReceive('post')
            ->with('https://gw1.cardsaveonlinepayments.com:4430/', m::type('string'), m::type('array'))->once()
            ->andReturn('<Response><Elem><Elem><CardDetailsTransactionResult><StatusCode>0</StatusCode></CardDetailsTransactionResult><TransactionOutputData CrossReference="abc123" /></Elem></Elem></Response>');

        $response = $this->gateway->purchase($this->request, $this->card);

        $this->assertInstanceOf('\Tala\Billing\CardSave\Response', $response);
        $this->assertEquals('abc123', $response->getGatewayReference());
    }
}
