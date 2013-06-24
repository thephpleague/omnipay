<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\SagePay;

use Omnipay\GatewayTestCase;

class DirectGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new DirectGateway($this->getHttpClient(), $this->getHttpRequest());

        $this->purchaseOptions = array(
            'amount' => '10.00',
            'transactionId' => '123',
            'card' => $this->getValidCard(),
            'returnUrl' => 'https://www.example.com/return',
        );

        $this->captureOptions = array(
            'amount' => '10.00',
            'transactionReference' => '{"SecurityKey":"JEUPDN1N7E","TxAuthNo":"4255","VPSTxId":"{F955C22E-F67B-4DA3-8EA3-6DAC68FA59D2}","VendorTxCode":"438791"}',
        );
    }

    public function testAuthorizeFailureSuccess()
    {
        $this->setMockHttpResponse('DirectPurchaseSuccess.txt');

        $response = $this->gateway->authorize($this->purchaseOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('{"SecurityKey":"OUWLNYQTVT","TxAuthNo":"9962","VPSTxId":"{5A1BC414-5409-48DD-9B8B-DCDF096CE0BE}","VendorTxCode":"123"}', $response->getTransactionReference());
        $this->assertSame('Direct transaction from Simulator.', $response->getMessage());
    }

    public function testAuthorizeFailure()
    {
        $this->setMockHttpResponse('DirectPurchaseFailure.txt');

        $response = $this->gateway->authorize($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('The VendorTxCode \'984297\' has been used before.  Each transaction you send should have a unique VendorTxCode.', $response->getMessage());
    }

    public function testAuthorize3dSecure()
    {
        $this->setMockHttpResponse('DirectPurchase3dSecure.txt');

        $response = $this->gateway->authorize($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertSame('https://test.sagepay.com/Simulator/3DAuthPage.asp', $response->getRedirectUrl());

        $redirectData = $response->getRedirectData();
        $this->assertSame('065379457749061954', $redirectData['MD']);
        $this->assertSame('BSkaFwYFFTYAGyFbAB0LFRYWBwsBZw0EGwECEX9YRGFWc08pJCVVKgAANS0KADoZCCAMBnIeOxcWRg0LERdOOTQRDFRdVHNYUgwTMBsBCxABJw4DJHE+ERgPCi8MVC0HIAROCAAfBUk4ER89DD0IWDkvMQ1VdFwoUFgwXVYvbHgvMkdBXXNbQGIjdl1ZUEc1XSwqAAgUUicYBDYcB3I2AjYjIzsn', $redirectData['PaReq']);
        $this->assertSame('https://www.example.com/return', $redirectData['TermUrl']);
    }

    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('DirectPurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->purchaseOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('{"SecurityKey":"OUWLNYQTVT","TxAuthNo":"9962","VPSTxId":"{5A1BC414-5409-48DD-9B8B-DCDF096CE0BE}","VendorTxCode":"123"}', $response->getTransactionReference());
        $this->assertSame('Direct transaction from Simulator.', $response->getMessage());
    }

    public function testPurchaseFailure()
    {
        $this->setMockHttpResponse('DirectPurchaseFailure.txt');

        $response = $this->gateway->purchase($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('The VendorTxCode \'984297\' has been used before.  Each transaction you send should have a unique VendorTxCode.', $response->getMessage());
    }

    public function testPurchase3dSecure()
    {
        $this->setMockHttpResponse('DirectPurchase3dSecure.txt');

        $response = $this->gateway->purchase($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertSame('https://test.sagepay.com/Simulator/3DAuthPage.asp', $response->getRedirectUrl());

        $redirectData = $response->getRedirectData();
        $this->assertSame('065379457749061954', $redirectData['MD']);
        $this->assertSame('BSkaFwYFFTYAGyFbAB0LFRYWBwsBZw0EGwECEX9YRGFWc08pJCVVKgAANS0KADoZCCAMBnIeOxcWRg0LERdOOTQRDFRdVHNYUgwTMBsBCxABJw4DJHE+ERgPCi8MVC0HIAROCAAfBUk4ER89DD0IWDkvMQ1VdFwoUFgwXVYvbHgvMkdBXXNbQGIjdl1ZUEc1XSwqAAgUUicYBDYcB3I2AjYjIzsn', $redirectData['PaReq']);
        $this->assertSame('https://www.example.com/return', $redirectData['TermUrl']);
    }

    public function testCaptureSuccess()
    {
        $this->setMockHttpResponse('CaptureSuccess.txt');

        $response = $this->gateway->capture($this->captureOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('The transaction was RELEASEed successfully.', $response->getMessage());
    }

    public function testCaptureFailure()
    {
        $this->setMockHttpResponse('CaptureFailure.txt');

        $response = $this->gateway->capture($this->captureOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('You are trying to RELEASE a transaction that has already been RELEASEd or ABORTed.', $response->getMessage());
    }

    public function testRefundSuccess()
    {
        $this->setMockHttpResponse('CaptureSuccess.txt');

        $response = $this->gateway->refund($this->captureOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('The transaction was RELEASEed successfully.', $response->getMessage());
    }

    public function testRefundFailure()
    {
        $this->setMockHttpResponse('CaptureFailure.txt');

        $response = $this->gateway->refund($this->captureOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('You are trying to RELEASE a transaction that has already been RELEASEd or ABORTed.', $response->getMessage());
    }
}
