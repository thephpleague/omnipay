<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\TargetPay\Message;

use Omnipay\TestCase;

class DirectebankingPurchaseRequestTest extends TestCase
{
    /**
     * @var PurchaseRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new DirectebankingPurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'country' => '32',
            'serviceType' => '1',
            'amount' => '100.00',
            'description' => 'easy, no?',
            'clientIp' => '127.0.0.1',
            'language' => 'EN',
            'returnUrl' => 'http://localhost/return',
            'notifyUrl' => 'http://localhost/notify',
        ));
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('DirectebankingPurchaseSuccess.txt');

        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('https://www.directebanking.com/payment/start?user_id=38165&project_id=98594&sender_holder=&sender_account_number=&sender_bank_code=&sender_country_id=BE&amount=100.00&currency_id=EUR&reason_1=1284687.easy%2C+no%3F&reason_2=VOLTEC+BV&user_variable_0=1284687&user_variable_1=&user_variable_2=&user_variable_3=&user_variable_4=&user_variable_5=&hash=a48af1cdf55877b84a66f9916d25e4a5d955e29f', $response->getRedirectUrl());
        $this->assertEquals('1284687', $response->getTransactionReference());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('DirectebankingPurchaseFailure.txt');

        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('Invalid or no type given', $response->getMessage());
        $this->assertEquals('TP0012', $response->getCode());
    }
}
