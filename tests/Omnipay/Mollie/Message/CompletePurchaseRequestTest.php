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

class CompletePurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'partnerId' => 'my partner id',
        ));
    }

    public function testGetData()
    {
        $this->getHttpRequest()->query->replace(array(
            'transaction_id' => 'abc123',
        ));

        $data = $this->request->getData();

        $this->assertSame('check', $data['a']);
        $this->assertSame('my partner id', $data['partnerid']);
        $this->assertSame('abc123', $data['transaction_id']);
    }

    /*
     * We need a Mollie test account to record some responses to completePurchase()
     * and test CompletePurchaseRequest::send()
     * Pull requests welcome!
     */
}
