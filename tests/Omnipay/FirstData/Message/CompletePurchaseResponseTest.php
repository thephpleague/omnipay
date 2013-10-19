<?php

namespace Omnipay\FirstData\Message;

use Omnipay\TestCase;

class CompletePurchaseResponseTest extends TestCase
{
    public function testCompletePurchaseSuccess()
    {
        $response = new CompletePurchaseresponse(
            $this->getMockRequest(),
            array(
                'chargetotal' => '110.00',
                'response_hash' => '796d7ca236576256236e92900dedfd55be08567a',
                'status' => 'APPROVED',
                'oid' => 'abc123456',
                'txndatetime' => '2013:09:27-16:06:26',
                'approval_code' => 'Y:136432:0013649958:PPXM:0015'
            )
        );

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('abc123456', $response->getTransactionReference());
        $this->assertSame('APPROVED', $response->getMessage());
    }

    public function testCompletePurchaseFailure()
    {
        $response = new CompletePurchaseresponse(
            $this->getMockRequest(),
            array(
                'chargetotal' => '110.00',
                'response_hash' => '0dfe9e4b3c6306343926207a8814a48f72087cc7',
                'status' => 'DECLINED',
                'oid' => 'abc1234',
                'txndatetime' => '2013:09:27-16:00:19',
                'approval_code' => 'N:05:DECLINED'
            )
        );

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('abc1234', $response->getTransactionReference());
        $this->assertSame('DECLINED', $response->getMessage());
    }
}
