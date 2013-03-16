<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PayFast\Message;

use Omnipay\TestCase;

class CompletePurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function getItnPostData()
    {
        return array(
            'm_payment_id' => '',
            'pf_payment_id' => '61493',
            'payment_status' => 'COMPLETE',
            'item_name' => 'fjdksl',
            'item_description' => '',
            'amount_gross' => '12.00',
            'amount_fee' => '-0.27',
            'amount_net' => '11.73',
            'custom_str1' => '',
            'custom_str2' => '',
            'custom_str3' => '',
            'custom_str4' => '',
            'custom_str5' => '',
            'custom_int1' => '',
            'custom_int2' => '',
            'custom_int3' => '',
            'custom_int4' => '',
            'custom_int5' => '',
            'name_first' => 'Test',
            'name_last' => 'User 01',
            'email_address' => 'sbtu01@payfast.co.za',
            'merchant_id' => '10000103',
            'signature' => '92ac916145511e9050383b008729e162',
        );
    }

    public function testCompletePurchaseItnSuccess()
    {
        $this->getHttpRequest()->request->replace($this->getItnPostData());
        $this->setMockHttpResponse('CompletePurchaseItnSuccess.txt');

        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\PayFast\Message\CompletePurchaseItnResponse', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('61493', $response->getTransactionReference());
        $this->assertSame('COMPLETE', $response->getMessage());
        $this->assertNull($response->getCode());
    }

    public function testCompletePurchaseItnInvalid()
    {
        $this->getHttpRequest()->request->replace($this->getItnPostData());
        $this->setMockHttpResponse('CompletePurchaseItnFailure.txt');

        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('INVALID', $response->getMessage());
        $this->assertNull($response->getCode());
    }

    public function testCompletePurchasePdtSuccess()
    {
        $this->getHttpRequest()->query->replace(array('pt' => 'abc'));
        $this->setMockHttpResponse('CompletePurchasePdtFailure.txt');

        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\PayFast\Message\CompletePurchasePdtResponse', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('FAIL', $response->getMessage());
        $this->assertNull($response->getCode());
    }
}
