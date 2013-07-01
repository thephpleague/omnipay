<?php

namespace Omnipay\NetBanx;

use Omnipay\Common\CreditCard;
use Omnipay\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

        $card = new CreditCard($this->getValidCard());

        $card->setBillingAddress1('Wall street');
        $card->setBillingAddress2('Wall street 2');
        $card->setBillingCity('San Luis Obispo');
        $card->setBillingCountry('US');
        $card->setBillingPostcode('93401');
        $card->setBillingPhone('1234567');
        $card->setBillingState('CA');

        $card->setShippingAddress1('Shipping Wall street');
        $card->setShippingAddress2('Shipping Wall street 2');
        $card->setShippingCity('San Luis Obispo');
        $card->setShippingCountry('US');
        $card->setShippingPostcode('93401');
        $card->setShippingPhone('1234567');
        $card->setShippingState('CA');

        $card->setCompany('Test Business name');
        $card->setEmail('test@example.com');

        $this->purchaseOptions = array(
            'amount' => '95.63',
            'card' => $card,
            'customerId' => '9966441',
        );

        $this->captureOptions = array(
            'amount' => '10.00',
            'transactionReference' => '9988775',
        );

        $this->voidOptions = array(
            'accountNumber' => '12345678',
            'storeId' => 'test',
            'storePassword' => 'test',
            'transactionReference' => '115147689',
        );

        $this->storedDataOptions = array(
            'amount' => '95.63',
            'customerId' => '9966441',
            'transactionReference' => '244530120',
        );
    }

    public function testAuthorizeSuccess()
    {
        $this->setMockHttpResponse('AuthorizeSuccess.txt');

        $request = $this->gateway->authorize($this->purchaseOptions);
        $requestData = $request->getData();
        /** @var $card CreditCard */
        $card = $request->getCard();

        $response = $request->send();

        $sxml = new \SimpleXMLElement($requestData['txnRequest']);

        $this->assertSame('ccAuthorize', $requestData['txnMode']);

        $this->assertSame('93401', (string) $sxml->billingDetails->zip);
        $this->assertSame('VI', (string) $sxml->card->cardType);

        $this->assertTrue(isset($sxml->billingDetails));
        $this->assertTrue(isset($sxml->shippingDetails));

        $this->assertSame('95.63', (string) $sxml->amount);
        $this->assertSame('9966441', (string) $sxml->merchantRefNum);
        $this->assertSame('93401', $card->getPostcode());
        $this->assertSame('test@example.com', $card->getEmail());

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('244350540', $response->getTransactionReference());
        $this->assertSame('No Error', $response->getMessage());
    }

    public function testAuthorizeFailure()
    {
        $this->setMockHttpResponse('AuthorizeFailure.txt');

        $response = $this->gateway->authorize($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('-1', $response->getTransactionReference());
        $this->assertSame('Invalid txnMode: ccccAuthorize', $response->getMessage());
    }

    public function testStoredDataAuthorizeSuccess()
    {
        $this->setMockHttpResponse('StoredDataAuthorizeSuccess.txt');

        $request = $this->gateway->authorize($this->storedDataOptions);
        $requestData = $request->getData();

        $response = $request->send();

        $sxml = new \SimpleXMLElement($requestData['txnRequest']);

        $this->assertSame('ccStoredDataAuthorize', $requestData['txnMode']);

        $this->assertSame('244530120', (string) $sxml->confirmationNumber);

        $this->assertSame('95.63', (string) $sxml->amount);
        $this->assertSame('9966441', (string) $sxml->merchantRefNum);

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('244530120', $response->getTransactionReference());
        $this->assertSame('No Error', $response->getMessage());
    }

    public function testStoredDataAuthorizeFailure()
    {
        $this->setMockHttpResponse('StoredDataAuthorizeFailure.txt');

        $response = $this->gateway->authorize($this->storedDataOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame(
            'You submitted an invalid XML request. Please verify your request and retry the transaction.',
            $response->getMessage()
        );
    }

    public function testCaptureSuccess()
    {
        $this->setMockHttpResponse('CaptureSuccess.txt');

        $request = $this->gateway->capture($this->captureOptions);
        $requestData = $request->getData();

        $response = $request->send();

        $this->assertSame('ccSettlement', $requestData['txnMode']);

        $sxml = new \SimpleXMLElement($requestData['txnRequest']);

        $this->assertSame('9988775', (string) $sxml->confirmationNumber);

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('9988775', $response->getTransactionReference());
        $this->assertSame('No Error', $response->getMessage());
    }

    public function testCaptureFailure()
    {
        $this->setMockHttpResponse('CaptureFailure.txt');

        $request = $this->gateway->capture($this->captureOptions);
        $requestData = $request->getData();

        $response = $request->send();

        $this->assertSame('ccSettlement', $requestData['txnMode']);

        $sxml = new \SimpleXMLElement($requestData['txnRequest']);

        $this->assertSame('9988775', (string) $sxml->confirmationNumber);

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('9988775', $response->getTransactionReference());
        $this->assertSame('The authorization is either fully settled or cancelled.', $response->getMessage());
    }

    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $request = $this->gateway->purchase($this->purchaseOptions);
        $requestData = $request->getData();
        /** @var $card CreditCard */
        $card = $request->getCard();

        $response = $request->send();

        $sxml = new \SimpleXMLElement($requestData['txnRequest']);

        $this->assertSame('ccPurchase', $requestData['txnMode']);

        $this->assertSame('93401', (string) $sxml->billingDetails->zip);

        $this->assertSame('93401', $card->getPostcode());
        $this->assertSame('test@example.com', $card->getEmail());

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('244356120', $response->getTransactionReference());
        $this->assertSame('No Error', $response->getMessage());
    }

    public function testPurchaseFailure()
    {
        $this->setMockHttpResponse('PurchaseFailure.txt');

        $request = $this->gateway->purchase($this->purchaseOptions);
        $requestData = $request->getData();
        /** @var $card CreditCard */
        $card = $request->getCard();

        $response = $request->send();

        $sxml = new \SimpleXMLElement($requestData['txnRequest']);

        $this->assertSame('ccPurchase', $requestData['txnMode']);

        $this->assertSame('93401', (string) $sxml->billingDetails->zip);

        $this->assertSame('93401', $card->getPostcode());
        $this->assertSame('test@example.com', $card->getEmail());

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('244356120', $response->getTransactionReference());
        $this->assertSame('You submitted an unsupported card type with your request.', $response->getMessage());
    }

    public function testStoredDataPurchaseSuccess()
    {
        $this->setMockHttpResponse('StoredDataPurchaseSuccess.txt');

        $request = $this->gateway->purchase($this->storedDataOptions);
        $requestData = $request->getData();

        $response = $request->send();

        $sxml = new \SimpleXMLElement($requestData['txnRequest']);

        $this->assertSame('ccStoredDataPurchase', $requestData['txnMode']);

        $this->assertSame('244530120', (string) $sxml->confirmationNumber);

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('244679250', $response->getTransactionReference());
        $this->assertSame('No Error', $response->getMessage());
    }

    public function testStoredDataPurchaseFailure()
    {
        $this->setMockHttpResponse('StoredDataPurchaseFailure.txt');

        $response = $this->gateway->purchase($this->storedDataOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame(
            'You submitted an invalid XML request. Please verify your request and retry the transaction.',
            $response->getMessage()
        );
    }

    public function testVoidSuccess()
    {
        $this->setMockHttpResponse('VoidSuccess.txt');

        $request = $this->gateway->void($this->voidOptions);
        $requestData = $request->getData();
        $response = $request->send();

        $sxml = new \SimpleXMLElement($requestData['txnRequest']);

        $this->assertSame('ccAuthorizeReversal', $requestData['txnMode']);

        $this->assertSame('12345678', (string) $sxml->merchantAccount->accountNum);
        $this->assertSame('test', (string) $sxml->merchantAccount->storeID);
        $this->assertSame('test', (string) $sxml->merchantAccount->storePwd);

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('12345678', $response->getTransactionReference());
        $this->assertSame('No Error', $response->getMessage());
    }

    public function testVoidFailure()
    {
        $this->setMockHttpResponse('VoidFailure.txt');

        $request = $this->gateway->void($this->voidOptions);
        $requestData = $request->getData();
        $response = $request->send();

        $sxml = new \SimpleXMLElement($requestData['txnRequest']);

        $this->assertSame('ccAuthorizeReversal', $requestData['txnMode']);

        $this->assertSame('12345678', (string) $sxml->merchantAccount->accountNum);
        $this->assertSame('test', (string) $sxml->merchantAccount->storeID);
        $this->assertSame('test', (string) $sxml->merchantAccount->storePwd);

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('12345678', $response->getTransactionReference());
        $this->assertSame('The confirmation number included in this request could not be found.', $response->getMessage());
    }

    public function testCreateCard()
    {
        $this->setMockHttpResponse('CreateCard.txt');

        $request = $this->gateway->createCard($this->purchaseOptions);
        $requestData = $request->getData();
        /** @var $card CreditCard */
        $card = $request->getCard();

        $response = $request->send();

        $sxml = new \SimpleXMLElement($requestData['txnRequest']);

        $this->assertSame('ccAuthorize', $requestData['txnMode']);

        $this->assertSame('93401', (string) $sxml->billingDetails->zip);
        $this->assertSame('VI', (string) $sxml->card->cardType);

        $this->assertTrue(isset($sxml->billingDetails));
        $this->assertTrue(isset($sxml->shippingDetails));

        $this->assertSame('1.00', (string) $sxml->amount);
        $this->assertSame('9966441', (string) $sxml->merchantRefNum);
        $this->assertSame('93401', $card->getPostcode());
        $this->assertSame('test@example.com', $card->getEmail());

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('244350540', $response->getCardReference());
        $this->assertSame('No Error', $response->getMessage());
    }
}
