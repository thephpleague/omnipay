<?php

namespace Omnipay\Agms;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setUsername('osdgithub');
        $this->gateway->setPassword('Ks1m32aF@');
        $this->gateway->setApiKey('accf69cefaeb1d19702e33b0a9bfc9f8f0ab3c065d937fc');
        $this->gateway->setAccountNumber('1002186');
        
    }

    public function testAuthorizeSuccess()
    {
        $this->options = array('card' => $this->getValidCard());
        // card numbers ending in even number should be successful
        $this->options['card']['number'] = '4242424242424242';
        $this->options['amount'] = '10.00';
            
        $response = $this->gateway->authorize($this->options)->send();

        $this->assertInstanceOf('\Omnipay\Agms\Message\Response', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Approved', $response->getMessage());
    }

    public function testAuthorizeFailure()
    {
        $this->options = array('card' => $this->getValidCard());
        // card numbers ending in odd number should be declined
        $this->options['card']['number'] = '4000000000000002';
        $this->options['amount'] = '0.01';
        
        $response = $this->gateway->authorize($this->options)->send();
        
        $this->assertInstanceOf('\Omnipay\Agms\Message\Response', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Declined', $response->getMessage());
    }

    public function testCaptureSuccess()
    {
        $this->options = array('card' => $this->getValidCard());
        // card numbers ending in even number should be successful
        $this->options['card']['number'] = '4242424242424242';
        $this->options['amount'] = '10.00';
        $response = $this->gateway->authorize($this->options)->send();
        
        $this->assertInstanceOf('\Omnipay\Agms\Message\Response', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Approved', $response->getMessage());
        
        // Reset options
        $this->options['card'] = NULL;
        
        $this->options['amount'] = '10.00';
        $this->options['transactionId'] = $response->getTransactionId();
        $response = $this->gateway->capture($this->options)->send();
        
        $this->assertInstanceOf('\Omnipay\Agms\Message\Response', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Capture successful: Approved', $response->getMessage());
    }

    public function testCaptureFailure()
    {
        $this->options = array('card' => $this->getValidCard());
        // card numbers ending in odd number should be declined
        $this->options['card']['number'] = '4000000000000002';
        $this->options['amount'] = '10.00';
        $response = $this->gateway->authorize($this->options)->send();
        
        $this->assertInstanceOf('\Omnipay\Agms\Message\Response', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertNotEmpty($response->getTransactionReference());
        $this->assertSame('Approved', $response->getMessage());

        // Reset options
        $this->options['card'] = NULL;
        
        $this->options['amount'] = '0.00';
        $this->options['transactionId'] = $response->getTransactionId();
        $response = $this->gateway->capture($this->options)->send();
        $this->assertInstanceOf('\Omnipay\Agms\Message\Response', $response);
        $this->assertFalse($response->isRedirect());
        $this->assertNotEmpty($response->getTransactionReference());
        
    }

    public function testPurchaseSuccess()
    {
        $this->options = array('card' => $this->getValidCard());
        // card numbers ending in even number should be successful
        $this->options['card']['number'] = '4242424242424242';
        $this->options['amount'] = '10.00';
        $response = $this->gateway->purchase($this->options)->send();

        $this->assertInstanceOf('\Omnipay\Agms\Message\Response', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Approved', $response->getMessage());
    }

    public function testPurcahseFailure()
    {
        $this->options = array('card' => $this->getValidCard());
        // card numbers ending in odd number should be declined
        $this->options['card']['number'] = '4111111111111111';
        $this->options['amount'] = '0.10';
        $response = $this->gateway->purchase($this->options)->send();
        
        $this->assertInstanceOf('\Omnipay\Agms\Message\Response', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNotEmpty($response->getTransactionReference());
        $this->assertSame('Declined', $response->getMessage());
    }

    public function testRefundSuccess()
    {
        $this->options = array('card' => $this->getValidCard());
        // card numbers ending in even number should be successful
        $this->options['card']['number'] = '4242424242424242';
        $this->options['amount'] = '10.00';
        $response = $this->gateway->purchase($this->options)->send();
        
        $this->assertInstanceOf('\Omnipay\Agms\Message\Response', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Approved', $response->getMessage());
        
        // Reset options
        $this->options['card'] = NULL;
        
        $this->options['amount'] = '09.00';
        $this->options['transactionId'] = $response->getTransactionId();
        $response = $this->gateway->refund($this->options)->send();
        
        $this->assertInstanceOf('\Omnipay\Agms\Message\Response', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('refund successful: Approved', $response->getMessage());
    }

    public function testRefundFailure()
    {
        $this->options = array('card' => $this->getValidCard());
        // card numbers ending in odd number should be declined
        $this->options['card']['number'] = '4000000000000002';
        $this->options['amount'] = '10.00';
        $response = $this->gateway->purchase($this->options)->send();
        
        $this->assertInstanceOf('\Omnipay\Agms\Message\Response', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNotEmpty($response->getTransactionReference());
        $this->assertSame('Approved', $response->getMessage());

        // Reset options
        $this->options['card'] = NULL;
        
        $this->options['amount'] = '0.00';
        $this->options['transactionId'] = $response->getTransactionId();
        $response = $this->gateway->refund($this->options)->send();

        $this->assertInstanceOf('\Omnipay\Agms\Message\Response', $response);
        $this->assertFalse($response->isRedirect());
        $this->assertNotEmpty($response->getTransactionReference());
        
    }

    public function testVoidSuccess()
    {
        $this->options = array('card' => $this->getValidCard());
        // card numbers ending in even number should be successful
        $this->options['card']['number'] = '4242424242424242';
        $this->options['amount'] = '10.00';
        $response = $this->gateway->purchase($this->options)->send();
        
        $this->assertInstanceOf('\Omnipay\Agms\Message\Response', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Approved', $response->getMessage());
        
        // Reset options
        $this->options['card'] = NULL;
        $this->options['transactionId'] = $response->getTransactionId();
        $response = $this->gateway->void($this->options)->send();
        
        $this->assertInstanceOf('\Omnipay\Agms\Message\Response', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('void successful: Approved', $response->getMessage());
    }

    public function testVoidFailure()
    {
        $this->options = array('card' => $this->getValidCard());
        // card numbers ending in odd number should be declined
        $this->options['card']['number'] = '4000000000000002';
        $this->options['amount'] = '10.00';
        $response = $this->gateway->purchase($this->options)->send();
        
        $this->assertInstanceOf('\Omnipay\Agms\Message\Response', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNotEmpty($response->getTransactionReference());
        $this->assertSame('Approved', $response->getMessage());

        // Reset options
        $this->options['card'] = NULL;
        $this->options['transactionId'] = '123456';
        $response = $this->gateway->void($this->options)->send();

        $this->assertInstanceOf('\Omnipay\Agms\Message\Response', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Transaction ID is not valid. Please double check your Transaction ID', $response->getMessage());
    }

    public function testCreateCard()
    {
        $this->options = array('card' => $this->getValidCard());
        
        $response = $this->gateway->createCard($this->options)->send();
        $this->assertInstanceOf('Omnipay\Agms\Message\Response', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('SAFE Record added successfully. No transaction processed.', $response->getMessage());
    }

    public function testUpdateCard()
    {
        $this->options = array('card' => $this->getValidCard());
        $response = $this->gateway->createCard($this->options)->send();
        $cardReference = $response->getCardReference();
        $this->options['cardReference'] = $cardReference;
        
        $response = $this->gateway->updateCard($this->options)->send();

        $this->assertInstanceOf('Omnipay\Agms\Message\Response', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame($cardReference, $response->getCardReference());
        $this->assertSame('SAFE Record updated successfully. No transaction processed.', $response->getMessage());
    }

    public function testDeleteCard()
    {
        $this->options = array('card' => $this->getValidCard());
        $response = $this->gateway->createCard($this->options)->send();
        $cardReference = $response->getCardReference();
        unset($this->options['card']);
        $this->options['cardReference'] = $cardReference;
        
        $response = $this->gateway->deleteCard($this->options)->send();

        $this->assertInstanceOf('Omnipay\Agms\Message\Response', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('SAFE record has been deactivated', $response->getMessage());
    }
}
