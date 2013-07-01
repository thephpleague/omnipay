<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Mollie\Message;

use Omnipay\TestCase;

class FetchIssuersRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new FetchIssuersRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('banklist', $data['a']);
    }

    public function testTestMode()
    {
        $this->assertArrayNotHasKey('testmode', $this->request->getData());

        $this->request->setTestMode(true);
        $data = $this->request->getData();

        $this->assertSame('true', $data['testmode']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('FetchIssuersSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('This is the current list of banks and their ID\'s that currently support iDEAL-payments', $response->getMessage());
        $this->assertNull($response->getCode());
        $this->assertSame(array('9999' => 'TBM Bank'), $response->getIssuers());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('FetchIssuersFailure.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('Did not receive a proper input value from you', $response->getMessage());
        $this->assertSame('-1', $response->getCode());
        $this->assertNull($response->getIssuers());
    }
}
