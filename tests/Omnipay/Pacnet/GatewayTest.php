<?php

namespace Omnipay\Pacnet;

use Omnipay\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

        $this->gateway->setUserName('ernest');
        $this->gateway->setPRN('840033');
        $this->gateway->setSharedSecret('all good men die young');

        $this->options = array(
            'amount'    => '10.00',
            'currency'  => 'USD',
            'card'      => array(
                'number'        => '4000000000000010',
                'expiryMonth'   => '09',
                'expiryYear'    => '2019',
                'cvv'           => '123'
            )
        );
    }

    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('10000160381', $response->getTransactionReference());
        $this->assertEquals('', $response->getMessage());
    }
}
