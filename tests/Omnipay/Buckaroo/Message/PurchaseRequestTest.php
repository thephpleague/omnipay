<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Buckaroo\Message;

use Omnipay\TestCase;

class PurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'merchantId' => 'merchant id',
                'secret' => 'shhhh',
                'amount' => '12.00',
                'returnUrl' => 'https://www.example.com/return',
            )
        );
    }

    public function testGetData()
    {
        $this->request->initialize(array(
            'merchantId' => 'merchant id',
            'secret' => 'shhhh',
            'amount' => '12.00',
            'currency' => 'EUR',
            'testMode' => true,
            'transactionId' => 13,
            'returnUrl' => 'https://www.example.com/return',
        ));

        $data = $this->request->getData();

        $this->assertSame('merchant id', $data['BPE_Merchant']);
        $this->assertSame(1200, $data['BPE_Amount']);
        $this->assertSame('EUR', $data['BPE_Currency']);
        $this->assertSame('EN', $data['BPE_Language']);
        $this->assertSame(1, $data['BPE_Mode']);
        $this->assertSame(13, $data['BPE_Invoice']);
        $this->assertSame('https://www.example.com/return', $data['BPE_Return_Success']);
        $this->assertSame('https://www.example.com/return', $data['BPE_Return_Reject']);
        $this->assertSame('https://www.example.com/return', $data['BPE_Return_Error']);
        $this->assertSame('POST', $data['BPE_Return_Method']);
        $this->assertSame('a22b9bd563f52e0a3e8e28998f9f6a12', $data['BPE_Signature2']);
    }

    public function testGenerateSignature()
    {
        $this->request->setSecret('abcdef');
        $data = array(
            'BPE_Merchant' => 'Tony Stark',
            'BPE_Invoice' => '99',
            'BPE_Amount' => '10000',
            'BPE_Currency' => 'ZAR',
            'BPE_Mode' => '1',
        );

        $this->assertSame('e93fea2554382e199df4dcf5fe74c1c6', $this->request->generateSignature($data));
    }

    public function testSend()
    {
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame('POST', $response->getRedirectMethod());
        $this->assertSame('https://payment.buckaroo.nl/sslplus/request_for_authorization.asp', $response->getRedirectUrl());
        $this->assertSame($this->request->getData(), $response->getRedirectData());
    }
}
