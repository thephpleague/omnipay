<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Icepay\Message;

use Omnipay\TestCase;
use ReflectionMethod;

class PurchaseRequestTest extends TestCase
{
    /**
     * @var PurchaseRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'merchantId' => '111111',
            'secretCode' => '222222',
            'transactionId' => '123456',
            'amount' => '100.00',
            'currency' => 'EUR',
            'paymentMethod' => 'IDEAL',
            'issuer' => 'ABNAMRO',
            'card' => array(
                'email' => 'something@example.com'
            ),
        ));
    }

    /**
     * @covers \Omnipay\Icepay\Message\PurchaseRequest::generateSignature()
     */
    public function testGenerateSignature()
    {
        $method = new ReflectionMethod('\Omnipay\Icepay\Message\PurchaseRequest', 'generateSignature');
        $method->setAccessible(true);

        $signature = $method->invoke($this->request);
        $this->assertEquals('96bed587552ba0ed7aec589388e73294cfffef50', $signature);
    }
}
