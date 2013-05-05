<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Migs\Message;

use Omnipay\TestCase;

class ThreePartyCompletePurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new ThreePartyCompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testThreePartyCompletePurchaseSuccess()
    {
        $data = array();

        $data['vpc_Message']         = "Approved";
        $data['vpc_ReceiptNo']       = "12345";
        $data['vpc_TxnResponseCode'] = "0";
        $data['vpc_SecureHash']      = "8720B88CA00352B2A5F4D51C64E86BCB";

        $response = new Response($this->getMockRequest(), $data);

        $this->assertInstanceOf('Omnipay\Migs\Message\Response', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('12345', $response->getTransactionReference());
        $this->assertSame('Approved', $response->getMessage());
        $this->assertNull($response->getCode());
    }

    public function testThreePartyCompletePurchaseFailure()
    {
        $data = array();

        $data['vpc_Message']         = "Error";
        $data['vpc_ReceiptNo']       = "12345";
        $data['vpc_TxnResponseCode'] = "1";
        $data['vpc_SecureHash']      = "8720B88CA00352B2A5F4D51C64E86BCB";

        $response = new Response($this->getMockRequest(), $data);

        $this->assertInstanceOf('Omnipay\Migs\Message\Response', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('12345', $response->getTransactionReference());
        $this->assertNotSame('Approved', $response->getMessage());
        $this->assertNull($response->getCode());
    }
}
