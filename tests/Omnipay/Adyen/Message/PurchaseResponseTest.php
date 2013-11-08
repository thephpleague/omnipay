<?php

namespace Omnipay\Adyen\Message;

use Omnipay\TestCase;

class PurchaseResponseTest extends TestCase
{
    public function testPurchaseSuccess()
    {
        $response = new PurchaseResponse($this->getMockRequest(), array(
            'amount' => '10.00',
            'currency' => 'EUR',
            'merchantReference' => 'TEST-10000',
            'shipBeforeDate' => date('Y-m-d', time()),
            'skinCode' => '05cp1ZtM',
            'sessionValidity' => '2013-11-05T11:27:59',
            'merchantAccount' => 'testacc',
            'secret' => 'test',
            'shopperLocale' => 'en_GB'
        ));

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertSame('POST', $response->getRedirectMethod());
    }
}
