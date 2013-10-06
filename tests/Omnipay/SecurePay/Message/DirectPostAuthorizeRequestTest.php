<?php

namespace Omnipay\SecurePay\Message;

use Omnipay\TestCase;

class DirectPostAuthorizeRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new DirectPostAuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->request->initialize(
            array(
                'merchantId' => 'foo',
                'transactionPassword' => 'bar',
                'amount' => '12.00',
                'returnUrl' => 'https://www.example.com/return',
            )
        );
    }

    public function testFingerprint()
    {
        // force timestamp for testing
        $data = $this->request->getData();
        $data['EPS_TIMESTAMP'] = '20130416123332';

        $this->assertSame('46b6a59173c9fea66f71b8679558837895f0bce8', $this->request->generateFingerprint($data));
    }

    public function testSend()
    {
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\SecurePay\Message\DirectPostAuthorizeResponse', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getCode());

        $this->assertSame('https://api.securepay.com.au/live/directpost/authorise', $response->getRedirectUrl());
        $this->assertSame('POST', $response->getRedirectMethod());

        $data = $response->getData();
        $this->assertArrayHasKey('EPS_FINGERPRINT', $data);
        $this->assertSame('1', $data['EPS_TXNTYPE']);
    }
}
