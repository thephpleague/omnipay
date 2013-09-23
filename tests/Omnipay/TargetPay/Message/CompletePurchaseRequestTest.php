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

class CompletePurchaseRequestTest extends TestCase
{
    /**
     * @var CompletePurchaseRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'paymentMethod' => 'mrcash',
            'transactionId' => '123456',
            'exchangeOnce' => true,
        ));
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('CompletePurchaseSuccess.txt');

        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('CompletePurchaseFailure.txt');

        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('Transaction was cancelled', $response->getMessage());
        $this->assertEquals('TP0013', $response->getCode());
    }
}
