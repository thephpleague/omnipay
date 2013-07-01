<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Buckaroo\Message;

use Omnipay\TestCase;

class CompletePurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'merchantId' => 'merchant id',
            'transactionId' => 13,
            'secret' => 'shhhh',
            'amount' => '12.00',
            'currency' => 'ZAR',
            'testMode' => true,
        ));
        $this->getHttpRequest()->request->replace(array(
            'bpe_signature2' => '351fad9e06e1aa041ec48726e0d98b81',
            'bpe_trx' => 'tricky',
            'bpe_timestamp' => '123456',
            'bpe_result' => 'success',
        ));
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame($this->getHttpRequest()->request->all(), $data);
    }

    /**
     * @expectedException Omnipay\Common\Exception\InvalidRequestException
     */
    public function testGetDataInvalidSignature()
    {
        $this->getHttpRequest()->request->set('bpe_signature2', 'zzz');

        $this->request->getData();
    }

    public function testGenerateResponseSignature()
    {
        $this->request->initialize(array(
            'merchantId' => 'merchant id',
            'transactionId' => 13,
            'secret' => 'shhhh',
            'amount' => '12.00',
            'currency' => 'ZAR',
            'testMode' => true,
        ));
        $this->getHttpRequest()->request->replace(array(
            'bpe_trx' => 'tricky',
            'bpe_timestamp' => '123456',
            'bpe_result' => 'success',
        ));

        $this->assertSame('351fad9e06e1aa041ec48726e0d98b81', $this->request->generateResponseSignature());
    }

    public function testSendSuccess()
    {
        $this->getHttpRequest()->request->set('bpe_result', '100');
        $this->getHttpRequest()->request->set('bpe_signature2', $this->request->generateResponseSignature());
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('tricky', $response->getTransactionReference());
        $this->assertSame('100', $response->getCode());
    }

    public function testSendError()
    {
        $this->getHttpRequest()->request->set('bpe_result', '999');
        $this->getHttpRequest()->request->set('bpe_signature2', $this->request->generateResponseSignature());
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('tricky', $response->getTransactionReference());
        $this->assertSame('999', $response->getCode());
    }
}
