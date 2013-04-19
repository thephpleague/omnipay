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
use Omnipay\Common\CreditCard;

class TwoPartyPurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new TwoPartyPurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testSignature()
    {
        $this->request->initialize(
            array(
                'amount' => 1200,
                'transactionId' => 123,
                'card' => new CreditCard(
                    array(
                        'number' => '4111111111111111',
                        'expiryMonth' => '05',
                        'expiryYear' => '2013',
                        'cvv' => '123',
                    )
                ),
                'merchantId'                   => '123',
                'merchantAccessCode'           => '123',
                'secureHash'                   => '123',
                'returnUrl' => 'https://www.example.com/return'
            )
        );

        $data = $this->request->getData();

        $this->assertSame('2624B4BABED7CCA98665238D75560600', $data['vpc_SecureHash']);
    }

    public function testPurchase()
    {
        $this->setMockHttpResponse('TwoPartyPurchaseSuccess.txt');

        $this->request->initialize(
            array(
                'amount' => 1200,
                'transactionId' => 123,
                'card' => new CreditCard(
                    array(
                        'number' => '4111111111111111',
                        'expiryMonth' => '05',
                        'expiryYear' => '2013',
                        'cvv' => '123',
                    )
                ),
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
