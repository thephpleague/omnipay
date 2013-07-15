<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\MultiSafepay;

use Omnipay\Common\CreditCard;
use Omnipay\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /**
     * @var Gateway
     */
    protected $gateway;

    /**
     * @var array
     */
    protected $options;

    protected function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setAccountId('111111');
        $this->gateway->setSiteId('222222');
        $this->gateway->setSiteCode('333333');

        $this->options = array(
            'notifyUrl' => 'http://localhost/notify',
            'cancelUrl' => 'http://localhost/cancel',
            'returnUrl' => 'http://localhost/return',
            'gateway' => 'IDEAL',
            'issuer' => 'issuer',
            'transactionId' => '123456',
            'currency' => 'EUR',
            'amount' => '100.00',
            'description' => 'desc',
            'extraData1' => 'extra 1',
            'extraData2' => 'extra 2',
            'extraData3' => 'extra 3',
            'language' => 'a language',
            'clientIp' => '127.0.0.1',
            'googleAnalyticsCode' => 'analytics code',
            'card' => array(
                'email' => 'something@example.com',
                'firstName' => 'first name',
                'lastName' => 'last name',
                'address1' => 'address 1',
                'address2' => 'address 2',
                'postcode' => '1000',
                'city' => 'a city',
                'country' => 'a country',
                'phone' => 'phone number',
            )
        );
    }

    public function testFetchPaymentMethods()
    {
        /** @var \Omnipay\MultiSafepay\Message\FetchPaymentMethodsRequest $request */
        $request = $this->gateway->fetchPaymentMethods(array('country' => 'NL'));

        $this->assertInstanceOf('Omnipay\MultiSafepay\Message\FetchPaymentMethodsRequest', $request);
        $this->assertEquals('NL', $request->getCountry());
    }

    public function testFetchIssuers()
    {
        /** @var \Omnipay\MultiSafepay\Message\FetchIssuersRequest $request */
        $request = $this->gateway->fetchIssuers();

        $this->assertInstanceOf('Omnipay\MultiSafepay\Message\FetchIssuersRequest', $request);
    }

    public function testPurchase()
    {
        /** @var \Omnipay\MultiSafepay\Message\PurchaseRequest $request */
        $request = $this->gateway->purchase($this->options);

        /** @var CreditCard $card */
        $card = $request->getCard();

        $this->assertInstanceOf('Omnipay\MultiSafepay\Message\PurchaseRequest', $request);
        $this->assertSame('http://localhost/notify', $request->getNotifyUrl());
        $this->assertSame('http://localhost/cancel', $request->getCancelUrl());
        $this->assertSame('http://localhost/return', $request->getReturnUrl());
        $this->assertSame('IDEAL', $request->getGateway());
        $this->assertSame('issuer', $request->getIssuer());
        $this->assertSame('123456', $request->getTransactionId());
        $this->assertSame('EUR', $request->getCurrency());
        $this->assertSame('100.00', $request->getAmount());
        $this->assertSame('desc', $request->getDescription());
        $this->assertSame('extra 1', $request->getExtraData1());
        $this->assertSame('extra 2', $request->getExtraData2());
        $this->assertSame('extra 3', $request->getExtraData3());
        $this->assertSame('a language', $request->getLanguage());
        $this->assertSame('analytics code', $request->getGoogleAnalyticsCode());
        $this->assertSame('127.0.0.1', $request->getClientIp());
        $this->assertSame('something@example.com', $card->getEmail());
        $this->assertSame('first name', $card->getFirstName());
        $this->assertSame('last name', $card->getLastName());
        $this->assertSame('address 1', $card->getAddress1());
        $this->assertSame('address 2', $card->getAddress2());
        $this->assertSame('1000', $card->getPostcode());
        $this->assertSame('a city', $card->getCity());
        $this->assertSame('a country', $card->getCountry());
        $this->assertSame('phone number', $card->getPhone());
    }

    public function testCompletePurchase()
    {
        /** @var \Omnipay\MultiSafepay\Message\CompletePurchaseRequest $request */
        $request = $this->gateway->completePurchase($this->options);

        /** @var CreditCard $card */
        $card = $request->getCard();

        $this->assertInstanceOf('Omnipay\MultiSafepay\Message\CompletePurchaseRequest', $request);
        $this->assertSame('http://localhost/notify', $request->getNotifyUrl());
        $this->assertSame('http://localhost/cancel', $request->getCancelUrl());
        $this->assertSame('http://localhost/return', $request->getReturnUrl());
        $this->assertSame('IDEAL', $request->getGateway());
        $this->assertSame('issuer', $request->getIssuer());
        $this->assertSame('123456', $request->getTransactionId());
        $this->assertSame('EUR', $request->getCurrency());
        $this->assertSame('100.00', $request->getAmount());
        $this->assertSame('desc', $request->getDescription());
        $this->assertSame('extra 1', $request->getExtraData1());
        $this->assertSame('extra 2', $request->getExtraData2());
        $this->assertSame('extra 3', $request->getExtraData3());
        $this->assertSame('a language', $request->getLanguage());
        $this->assertSame('analytics code', $request->getGoogleAnalyticsCode());
        $this->assertSame('127.0.0.1', $request->getClientIp());
        $this->assertSame('something@example.com', $card->getEmail());
        $this->assertSame('first name', $card->getFirstName());
        $this->assertSame('last name', $card->getLastName());
        $this->assertSame('address 1', $card->getAddress1());
        $this->assertSame('address 2', $card->getAddress2());
        $this->assertSame('1000', $card->getPostcode());
        $this->assertSame('a city', $card->getCity());
        $this->assertSame('a country', $card->getCountry());
        $this->assertSame('phone number', $card->getPhone());
    }
}
