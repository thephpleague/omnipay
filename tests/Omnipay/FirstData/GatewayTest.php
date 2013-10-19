<?php

namespace Omnipay\FirstData;

use Omnipay\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new ConnectGateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setSharedSecret('96MbdNvxTa');
        $this->gateway->setStoreId('1120540155');

        $this->options = array(
            'amount' => '13.00',
            'returnUrl' => 'https://www.example.com/return',
            'card' => $this->getValidCard(),
            'transactionId' => 'abc123',
            'currency' => 'GBP'
        );
    }

    public function testPurchase()
    {
        $response = $this->gateway->purchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertContains('ipg-online.com/connect/gateway/processing', $response->getRedirectUrl());
    }

    public function testCompletePurchaseSuccess()
    {
        $this->getHttpRequest()->request->replace(
            array(
                'chargetotal' => '110.00',
                'response_hash' => '796d7ca236576256236e92900dedfd55be08567a',
                'status' => 'APPROVED',
                'oid' => 'abc123456',
                'txndatetime' => '2013:09:27-16:06:26',
                'approval_code' => 'Y:136432:0013649958:PPXM:0015'
            )
        );

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('abc123456', $response->getTransactionReference());
        $this->assertSame('APPROVED', $response->getMessage());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidResponseException
     */
    public function testCompletePurchaseInvalidCallbackPassword()
    {
        $this->getHttpRequest()->request->replace(
            array(
                'chargetotal' => '110.00',
                'response_hash' => 'FAKE',
                'status' => 'APPROVED',
                'oid' => 'abc123456',
                'txndatetime' => '2013:09:27-16:06:26',
                'approval_code' => 'Y:136432:0013649958:PPXM:0015'
            )
        );

        $response = $this->gateway->completePurchase($this->options)->send();
    }

    public function testCompletePurchaseError()
    {
        $this->getHttpRequest()->request->replace(
            array(
                'chargetotal' => '110.00',
                'response_hash' => '0dfe9e4b3c6306343926207a8814a48f72087cc7',
                'status' => 'DECLINED',
                'oid' => 'abc1234',
                'txndatetime' => '2013:09:27-16:00:19',
                'approval_code' => 'N:05:DECLINED'
            )
        );

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('abc1234', $response->getTransactionReference());
        $this->assertSame('DECLINED', $response->getMessage());
    }
}
