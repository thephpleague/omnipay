<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\SagePay\Message;

use Omnipay\TestCase;

class RefundRequestTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '12.00',
                'transactionReference' => '{"SecurityKey":"JEUPDN1N7E","TxAuthNo":"4255","VPSTxId":"{F955C22E-F67B-4DA3-8EA3-6DAC68FA59D2}","VendorTxCode":"438791"}',
                'testMode' => true,
            )
        );
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('REFUND', $data['TxType']);
        $this->assertSame('12.00', $data['Amount']);
        $this->assertSame('438791', $data['RelatedVendorTxCode']);
        $this->assertSame('{F955C22E-F67B-4DA3-8EA3-6DAC68FA59D2}', $data['RelatedVPSTxId']);
        $this->assertSame('JEUPDN1N7E', $data['RelatedSecurityKey']);
        $this->assertSame('4255', $data['RelatedTxAuthNo']);
    }

    public function testGetEndpoint()
    {
        $url = $this->request->getEndpoint();

        $this->assertSame('https://test.sagepay.com/gateway/service/refund.vsp', $url);
    }
}
