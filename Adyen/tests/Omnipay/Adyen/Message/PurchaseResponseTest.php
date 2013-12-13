<?php

namespace Omnipay\Adyen\Message;

use Omnipay\Tests\TestCase;

class PurchaseResponseTest extends TestCase
{
    
    public function setUp()
    {
        $this->response = new PurchaseResponse($this->getMockRequest(), array(
            'amount' => '10.00',
            'currency' => 'EUR',
            'merchantReference' => 'TEST-10000',
            'shipBeforeDate' => date('Y-m-d', time()),
            'skinCode' => '05cp1ZtM',
            'sessionValidity' => '2013-11-05T11:27:59',
            'merchantAccount' => 'testacc',
            'secret' => 'test',
            'shopperLocale' => 'en_GB',
            'endPoint' => 'https://test.adyen.com/hpp/pay.shtml',
            
        ));
    }
    public function testIsSuccessful()
    {
        $this->assertFalse($this->response->isSuccessful());
    }
    
    public function testIsRedirect()
    {
        $this->assertTrue($this->response->isRedirect());
    }
    
    public function testGetRedirectUrl()
    {
        $endPoint = $this->response->getData();
        $this->assertSame('https://test.adyen.com/hpp/pay.shtml', $endPoint['endPoint']);
    }
    
    public function testGetRedirectData()
    {
        $this->assertSame(count($this->response->getRedirectData()), 10);
    }
    
    public function testRedirectMethod()
    {
        $this->assertSame('POST', $this->response->getRedirectMethod());
    }
}
