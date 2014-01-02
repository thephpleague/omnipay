<?php

namespace Omnipay\Alipay;

use Omnipay\Alipay\Message\PurchaseResponse;
use Omnipay\Tests\GatewayTestCase;

class ExpressGatewayTest extends GatewayTestCase
{

    /**
     * @var ExpressGateway $gateway
     */
    protected $gateway;

    protected $options;

    public function setUp()
    {
        parent::setUp();
        $this->gateway = new ExpressGateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setPartner('20880127040');
        $this->gateway->setKey('sc1n78r0faswga7jjrpf6o');
        $this->gateway->setSellerEmail('example@qq.com');
        $this->gateway->setNotifyUrl('https://www.example.com/notify');
        $this->gateway->setReturnUrl('https://www.example.com/return');
        $this->options = array(
            'out_trade_no' => '2014010122390001',
            'subject'      => 'test',
            'total_fee'    => '0.01',
        );
    }

    public function testPurchase()
    {
        /**
         * @var PurchaseResponse $response
         */
        $response = $this->gateway->purchase($this->options)->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNotEmpty($response->getRedirectUrl());
        $redirectData = $response->getRedirectData();
        $this->assertSame('https://www.example.com/return', $redirectData['return_url']);
    }
}
