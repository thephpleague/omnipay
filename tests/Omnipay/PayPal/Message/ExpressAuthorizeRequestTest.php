<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PayPal\Message;

use Omnipay\TestCase;

class ExpressAuthorizeRequestTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->request = new ExpressAuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => 1000,
                'returnUrl' => 'https://www.example.com/return',
                'cancelUrl' => 'https://www.example.com/cancel',
            )
        );
    }

    public function testGetData()
    {
        $this->request->initialize(array(
            'amount' => 1000,
            'currency' => 'AUD',
            'transactionId' => '111',
            'description' => 'Order Description',
            'returnUrl' => 'https://www.example.com/return',
            'cancelUrl' => 'https://www.example.com/cancel',
            'notifyUrl' => 'https://www.example.com/notify',
            'headerImageUrl' => 'https://www.example.com/header.jpg',
        ));

        $data = $this->request->getData();

        $this->assertSame('10.00', $data['PAYMENTREQUEST_0_AMT']);
        $this->assertSame('AUD', $data['PAYMENTREQUEST_0_CURRENCYCODE']);
        $this->assertSame('111', $data['PAYMENTREQUEST_0_INVNUM']);
        $this->assertSame('Order Description', $data['PAYMENTREQUEST_0_DESC']);
        $this->assertSame('https://www.example.com/return', $data['RETURNURL']);
        $this->assertSame('https://www.example.com/cancel', $data['CANCELURL']);
        $this->assertSame('https://www.example.com/notify', $data['PAYMENTREQUEST_0_NOTIFYURL']);
        $this->assertSame('https://www.example.com/header.jpg', $data['HDRIMG']);
    }

    public function testHeaderImageUrl()
    {
        $this->assertSame($this->request, $this->request->setHeaderImageUrl('https://www.example.com/header.jpg'));
        $this->assertSame('https://www.example.com/header.jpg', $this->request->getHeaderImageUrl());

        $data = $this->request->getData();
        $this->assertEquals('https://www.example.com/header.jpg', $data['HDRIMG']);
    }
}
