<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\PayPal;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructEmpty()
    {
        $response = new Response(array());
        $this->assertNull($response->getGatewayReference());
    }

    public function testConstructRefundTransactionId()
    {
        $response = new Response(array('REFUNDTRANSACTIONID' => '11111'));
        $this->assertEquals('11111', $response->getGatewayReference());
    }

    public function testConstructTransactionId()
    {
        $response = new Response(array('TRANSACTIONID' => '22222'));
        $this->assertEquals('22222', $response->getGatewayReference());
    }

    public function testConstructPaymentTransactionId()
    {
        $response = new Response(array('PAYMENTINFO_0_TRANSACTIONID' => '33333'));
        $this->assertEquals('33333', $response->getGatewayReference());
    }

    public function testConstructData()
    {
        $data = array('example' => 'value');
        $response = new Response($data);
        $this->assertEquals($data, $response->getData());
    }
}
