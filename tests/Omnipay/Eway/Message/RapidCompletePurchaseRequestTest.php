<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Eway\Message;

use Omnipay\TestCase;

class RapidCompletePurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new RapidCompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'apiKey' => 'my api key',
            'password' => 'secret',
        ));
        $this->getHttpRequest()->query->replace(array(
            'AccessCode' => 'F9802j0-O7sdVLnOcb_3IPryTxHDtKY8u_0pb10GbYq-Xjvbc-5Bc_LhI-oBIrTxTCjhOFn7Mq-CwpkLDja5-iu-Dr3DjVTr9u4yxSB5BckdbJqSA4WWydzDO0jnPWfBdKcWL',
        ));
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('F9802j0-O7sdVLnOcb_3IPryTxHDtKY8u_0pb10GbYq-Xjvbc-5Bc_LhI-oBIrTxTCjhOFn7Mq-CwpkLDja5-iu-Dr3DjVTr9u4yxSB5BckdbJqSA4WWydzDO0jnPWfBdKcWL', $data['AccessCode']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('RapidCompletePurchaseRequestSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getRedirectUrl());
        $this->assertNull($response->getRedirectData());
        $this->assertSame('10204029', $response->getTransactionReference());
        $this->assertSame('A2000', $response->getMessage());
        $this->assertSame('A2000', $response->getCode());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('RapidCompletePurchaseRequestFailure.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getRedirectUrl());
        $this->assertNull($response->getRedirectData());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('V6021', $response->getMessage());
        $this->assertSame('V6021', $response->getCode());
    }
}
