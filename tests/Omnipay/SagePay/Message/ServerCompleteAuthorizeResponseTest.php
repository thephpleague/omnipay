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

class ServerCompleteAuthorizeResponseTest extends TestCase
{
    public function testServerCompleteAuthorizeResponseSuccess()
    {
        $response = new ServerCompleteAuthorizeResponse(
            $this->getMockRequest(),
            array(
                'Status' => 'OK',
                'TxAuthNo' => 'b',
                'AVSCV2' => 'c',
                'AddressResult' => 'd',
                'PostCodeResult' => 'e',
                'CV2Result' => 'f',
                'GiftAid' => 'g',
                '3DSecureStatus' => 'h',
                'CAVV' => 'i',
                'AddressStatus' => 'j',
                'PayerStatus' => 'k',
                'CardType' => 'l',
                'Last4Digits' => 'm',
            )
        );

        $this->getMockRequest()->shouldReceive('getTransactionId')->once()->andReturn('123');
        $this->getMockRequest()->shouldReceive('getTransactionReference')->once()->andReturn('{"SecurityKey":"JEUPDN1N7E","TxAuthNo":"4255","VPSTxId":"{F955C22E-F67B-4DA3-8EA3-6DAC68FA59D2}","VendorTxCode":"438791"}');

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('{"SecurityKey":"JEUPDN1N7E","TxAuthNo":"b","VPSTxId":"{F955C22E-F67B-4DA3-8EA3-6DAC68FA59D2}","VendorTxCode":"123"}', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testServerCompleteAuthorizeResponseFailure()
    {
        $response = new ServerCompleteAuthorizeresponse($this->getMockRequest(), array('Status' => 'INVALID'));

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }
}
