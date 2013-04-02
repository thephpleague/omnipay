<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Migs;

use Omnipay\GatewayTestCase;

class ThreePartyGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new ThreePartyGateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = array(
            'amount'        => 1000,
            'transactionId' => 12345,
            'returnUrl'     => 'https://www.example.com/return',
        );
    }

    public function testPurchase()
    {
        $response = $this->gateway->purchase($this->options)->send();
        
        $this->assertInstanceOf('\Omnipay\Migs\Message\ThreePurchaseResponse', $response);
        $this->assertTrue($response->isRedirect());
        $this->assertStringStartsWith('https://migs.mastercard.com.au/vpcpay?', $response->getRedirectUrl());
    }

    public function testCompletePurchase()
    {
        $this->getHttpRequest()->query->replace(
            array(
                'vpc_TxnResponseCode' => '0',
                'vpc_Message'         => 'Approved',
                'vpc_ReceiptNo'       => '12345',
                'vpc_SecureHash'      => '6EF34310C56872C53B2292C0AE22C8C8',
            )
        );

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('12345', $response->getTransactionReference());
        $this->assertSame('Approved', $response->getMessage());
    }
}
