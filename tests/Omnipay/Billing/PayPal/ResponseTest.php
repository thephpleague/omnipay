<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Billing\PayPal;

use Omnipay\TestCase;

class ResponseTest extends TestCase
{
    public function testConstructEmpty()
    {
        $response = new Response(array());
        $this->assertNull($response->getGatewayReference());
    }

    public function testConstructRefundTransactionId()
    {
        $response = new Response(array('REFUNDTRANSACTIONID' => '11111'));

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('11111', $response->getGatewayReference());
    }

    public function testConstructTransactionId()
    {
        $response = new Response(array('TRANSACTIONID' => '22222'));

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('22222', $response->getGatewayReference());
    }

    public function testConstructPaymentTransactionId()
    {
        $response = new Response(array('PAYMENTINFO_0_TRANSACTIONID' => '33333'));

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('33333', $response->getGatewayReference());
    }

    public function testConstructData()
    {
        $data = array('example' => 'value');
        $response = new Response($data);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals($data, $response->getData());
    }
}
