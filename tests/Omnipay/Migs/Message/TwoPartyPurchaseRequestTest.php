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

class TwoPartyPurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new TwoPartyPurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testCalculateHash()
    {
        $data = array(
            'vpc_Merchant' => '123',
            'vpc_AccessCode' => '123',
            'vpc_Version' => '1',
            'vpc_Locale' => 'en',
            'vpc_Command' => 'pay',
            'vpc_Amount' => '1200',
            'vpc_MerchTxnRef' => '123',
            'vpc_OrderInfo' => '',
            'vpc_ReturnURL' => 'https://www.example.com/return',
            'vpc_CardNum' => '4111111111111111',
            'vpc_CardExp' => '1305',
            'vpc_CardSecurityCode' => '123',
        );

        $this->request->setSecureHash('123');
        $hash = $this->request->calculateHash($data);

        $this->assertSame('2624B4BABED7CCA98665238D75560600', $hash);
    }

    public function testPurchase()
    {
        $this->setMockHttpResponse('TwoPartyPurchaseSuccess.txt');

        $this->request->initialize(
            array(
                'amount' => '12.00',
                'transactionId' => 123,
                'card' => $this->getValidCard(),
                'merchantId'                   => '123',
                'merchantAccessCode'           => '123',
                'secureHash'                   => '123',
                'returnUrl' => 'https://www.example.com/return'
            )
        );

        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\Migs\Message\Response', $response);

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('12345', $response->getTransactionReference());
        $this->assertSame('Approved', $response->getMessage());
        $this->assertNull($response->getCode());
        $this->assertArrayHasKey('vpc_SecureHash', $response->getData());
    }
}
