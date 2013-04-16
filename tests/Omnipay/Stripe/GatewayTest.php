<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Stripe;

use Omnipay\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setApiKey('abc123');

        $this->purchaseOptions = array(
            'amount' => 1000,
            'currency' => 'USD',
            'card' => $this->getValidCard(),
        );

        $this->refundOptions = array(
            'amount' => 1000,
            'transactionReference' => 'ch_12RgN9L7XhO9mI',
        );

        $this->storeOptions = array(
            'card' => $this->getValidCard(),
        );
        
        $this->updateOptions = array(
            'cardReference' => 'cus_1MZSEtqSghKx99',
        );

        $this->unstoreOptions = array(
            'cardReference' => 'cus_1MZSEtqSghKx99',
        );
    }

    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');
        $response = $this->gateway->purchase($this->purchaseOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('ch_1IU9gcUiNASROd', $response->getTransactionReference());
        $this->assertNull($response->getCardReference());
        $this->assertNull($response->getMessage());
    }

    public function testPurchaseError()
    {
        $this->setMockHttpResponse('PurchaseFailure.txt');
        $response = $this->gateway->purchase($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getCardReference());
        $this->assertSame('Your card was declined', $response->getMessage());
    }

    public function testRefundSuccess()
    {
        $this->setMockHttpResponse('RefundSuccess.txt');
        $response = $this->gateway->refund($this->refundOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('ch_12RgN9L7XhO9mI', $response->getTransactionReference());
        $this->assertNull($response->getCardReference());
        $this->assertNull($response->getMessage());
    }

    public function testRefundError()
    {
        $this->setMockHttpResponse('RefundFailure.txt');
        $response = $this->gateway->refund($this->refundOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getCardReference());
        $this->assertSame('Charge ch_12RgN9L7XhO9mI has already been refunded.', $response->getMessage());
    }

    public function testStoreSuccess()
    {
        $this->setMockHttpResponse('StoreSuccess.txt');
        $response = $this->gateway->store($this->storeOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('cus_1MZSEtqSghKx99', $response->getCardReference());
        $this->assertNull($response->getMessage());
    }

    public function testStoreFailure()
    {
        $this->setMockHttpResponse('StoreFailure.txt');
        $response = $this->gateway->store($this->storeOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getCardReference());
        $this->assertSame('You must provide an integer value for \'exp_year\'.', $response->getMessage());
    }
    
    public function testUpdateSuccess()
    {
        $this->setMockHttpResponse('UpdateSuccess.txt');
        $response = $this->gateway->store($this->storeOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('cus_1MZSEtqSghKx99', $response->getCardReference());
        $this->assertNull($response->getMessage());
    }

    public function testUpdateFailure()
    {
        $this->setMockHttpResponse('UpdateFailure.txt');
        $response = $this->gateway->store($this->storeOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getCardReference());
        $this->assertSame('No such customer: cus_1MZeNih5LdKxDq', $response->getMessage());
    }

    public function testUnstoreSuccess()
    {
        $this->setMockHttpResponse('UnstoreSuccess.txt');
        $response = $this->gateway->unstore($this->unstoreOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getCardReference());
        $this->assertNull($response->getMessage());
    }

    public function testUnstoreFailure()
    {
        $this->setMockHttpResponse('UnstoreFailure.txt');
        $response = $this->gateway->unstore($this->unstoreOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getCardReference());
        $this->assertSame('No such customer: cus_1MZeNih5LdKxDq', $response->getMessage());
    }
}
