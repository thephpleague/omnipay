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

        $this->gateway = new Gateway($this->httpClient, $this->httpRequest);

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
            ->andReturn('<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><CardDetailsTransactionResponse xmlns="https://www.thepaymentgateway.net/"><CardDetailsTransactionResult AuthorisationAttempted="True"><StatusCode>0</StatusCode><Message>AuthCode: 971112</Message></CardDetailsTransactionResult><TransactionOutputData CrossReference="130114063233159001702222"><AuthCode>971112</AuthCode><ThreeDSecureAuthenticationCheckResult>NOT_ENROLLED</ThreeDSecureAuthenticationCheckResult><GatewayEntryPoints><GatewayEntryPoint EntryPointURL="https://gw1.cardsaveonlinepayments.com:4430/" Metric="100" /><GatewayEntryPoint EntryPointURL="https://gw2.cardsaveonlinepayments.com:4430/" Metric="200" /></GatewayEntryPoints></TransactionOutputData></CardDetailsTransactionResponse></soap:Body></soap:Envelope>');

        $response = $this->gateway->purchase($this->request, $this->card);

        $this->assertInstanceOf('\Tala\Billing\CardSave\Response', $response);
        $this->assertEquals('130114063233159001702222', $response->getGatewayReference());
    }

    /**
     * @expectedException \Tala\Exception
     * @expectedExceptionMessage Input variable errors
     */
    public function testPurchaseError()
    {
        $this->httpRequest->shouldReceive('getClientIp')->once()->andReturn('127.0.0.1');

        $this->httpClient->shouldReceive('post')
            ->with('https://gw1.cardsaveonlinepayments.com:4430/', m::type('string'), m::type('array'))->once()
            ->andReturn('<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><CardDetailsTransactionResponse xmlns="https://www.thepaymentgateway.net/"><CardDetailsTransactionResult AuthorisationAttempted="False"><StatusCode>30</StatusCode><Message>Input variable errors</Message><ErrorMessages><MessageDetail><Detail>Required variable (PaymentMessage.TransactionDetails.OrderID) is missing</Detail></MessageDetail></ErrorMessages></CardDetailsTransactionResult><TransactionOutputData /></CardDetailsTransactionResponse></soap:Body></soap:Envelope>');

        $response = $this->gateway->purchase($this->request, $this->card);
    }
}
