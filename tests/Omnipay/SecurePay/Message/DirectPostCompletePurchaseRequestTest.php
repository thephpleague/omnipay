<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\SecurePay\Message;

use Omnipay\TestCase;

class DirectPostCompletePurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new DirectPostCompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testGenerateResponseFingerprint()
    {
        $this->request->initialize(array(
            'amount' => '465.18',
            'transactionPassword' => 'abc123',
        ));

        $data = array(
            'timestamp' => '20130602102927',
            'merchant' => 'ABC0030',
            'refid' => '222',
            'summarycode' => '2',
        );

        $this->assertSame('0516a31bf96ad89c354266afb9bd4be43aaf853f', $this->request->generateResponseFingerprint($data));
    }

    public function testSuccess()
    {
        $this->request->initialize(array(
            'amount' => '355.00',
            'transactionPassword' => 'abc123',
        ));

        $this->getHttpRequest()->request->replace(array(
            'timestamp' => '20130602112954',
            'callback_status_code' => '',
            'fingerprint' => 'd9b40fc6f841f41ef3475220fe6316406a5256ce',
            'txnid' => '205861',
            'merchant' => 'ABC0030',
            'restext' => 'Approved',
            'rescode' => '00',
            'expirydate' => '032016',
            'settdate' => '20130602',
            'refid' => '226',
            'pan' => '444433...111',
            'summarycode' => '1',
        ));

        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\\SecurePay\\Message\\DirectPostCompletePurchaseResponse', $response);

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('205861', $response->getTransactionReference());
        $this->assertSame('Approved', $response->getMessage());
        $this->assertSame('00', $response->getCode());
    }

    public function testFailure()
    {
        $this->request->initialize(array(
            'amount' => '465.18',
            'transactionPassword' => 'abc123',
        ));

        $this->getHttpRequest()->request->replace(array(
            'timestamp' => '20130602102927',
            'callback_status_code' => '',
            'fingerprint' => '0516a31bf96ad89c354266afb9bd4be43aaf853f',
            'txnid' => '205833',
            'merchant' => 'ABC0030',
            'restext' => 'Customer Dispute',
            'rescode' => '18',
            'expirydate' => '052016',
            'settdate' => '20130602',
            'refid' => '222',
            'pan' => '444433...111',
            'summarycode' => '2',
        ));

        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\\SecurePay\\Message\\DirectPostCompletePurchaseResponse', $response);

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('205833', $response->getTransactionReference());
        $this->assertSame('Customer Dispute', $response->getMessage());
        $this->assertSame('18', $response->getCode());
    }
}
