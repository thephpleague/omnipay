<?php

namespace Omnipay\Alipay;

use Omnipay\Alipay\Message\PurchaseResponse;
use Omnipay\Tests\GatewayTestCase;

class SecuredGatewayTest extends GatewayTestCase
{

    /**
     * @var SecuredGateway $gateway
     */
    protected $gateway;

    protected $options;

    public function setUp()
    {
        parent::setUp();
        $this->gateway = new SecuredGateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setPartner('20880127040');
        $this->gateway->setKey('sc1n78r0faswga7jjrpf6o');
        $this->gateway->setSellerEmail('example@qq.com');
        $this->gateway->setNotifyUrl('https://www.example.com/notify');
        $this->gateway->setReturnUrl('https://www.example.com/return');
        $this->gateway->setLogisticsInfo(
            8,
            SecuredGateway::LOGISTIC_TYPE_EMS,
            SecuredGateway::LOGISTIC_PAYMENT_BUYER_PAY
        );
        $this->gateway->setReceiveInfo('sqiu', 'shanghai', '201306', '15201234567', '15201234567');
        $this->options = array(
            'out_trade_no' => '2014010122390001',
            'subject'      => 'test',
            'price'        => '0.01',
            'quantity'     => '2',
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
