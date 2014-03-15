<?php
/**
 * Created by sqiu.
 * CreateTime: 14-3-6 下午12:40
 *
 */
namespace Omnipay\Alipay;

use Omnipay\Omnipay;
use Omnipay\Alipay\Message\PurchaseResponse;
use Omnipay\Tests\GatewayTestCase;

class CommonTest extends GatewayTestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->gateway       = Omnipay::create('Alipay_Express');
        $this->gateway->setPartner('000');
        $this->gateway->setKey('XXX');
        $this->gateway->setSellerEmail('aaa@bbb.com');
        $this->gateway->setNotifyUrl('https://www.example.com/notify');
        $this->gateway->setReturnUrl('https://www.example.com/return');
        $this->options = array(
            'out_trade_no' => '2014010122390001',
            'subject'      => 'test',
            'total_fee'    => '0.01',
        );

        $response = $this->gateway->purchase($this->options)->send();
        //echo 'alipay_pay_url:';
        //die($response->getRedirectUrl()); //debug the pay url, paste in browser, it will redirect.
        //$response->redirect();  //you can make a redirect use this method.
    }
}