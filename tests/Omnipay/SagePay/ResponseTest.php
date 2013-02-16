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

use Omnipay\Common\Request;
use Omnipay\TestCase;

class ResponseTest extends TestCase
{
    public function setUp()
    {
        $this->purchaseRequest = new Request(
            array(
                'transactionId' => '123',
                'returnUrl' => 'https://www.example.com/return',
            )
        );

        $this->captureRequest = new Request(
            array(
                'gatewayReference' => '{"SecurityKey":"JEUPDN1N7E","TxAuthNo":"4255","VPSTxId":"{F955C22E-F67B-4DA3-8EA3-6DAC68FA59D2}","VendorTxCode":"438791"}',
            )
        );
    }

    public function testDirectPurchaseSuccess()
    {
        $httpResponse = $this->getMockResponse('DirectPurchaseSuccess.txt');
        $response = Response::create($httpResponse->getBody(), $this->purchaseRequest);

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('{"SecurityKey":"OUWLNYQTVT","TxAuthNo":"9962","VPSTxId":"{5A1BC414-5409-48DD-9B8B-DCDF096CE0BE}","VendorTxCode":"123"}', $response->getGatewayReference());
        $this->assertSame('Direct transaction from Simulator.', $response->getMessage());
    }

    public function testDirectPurchaseFailure()
    {
        $httpResponse = $this->getMockResponse('DirectPurchaseFailure.txt');
        $response = Response::create($httpResponse->getBody(), $this->purchaseRequest);

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getGatewayReference());
        $this->assertSame('The VendorTxCode \'984297\' has been used before.  Each transaction you send should have a unique VendorTxCode.', $response->getMessage());
    }

    public function testDirectPurchase3dSecure()
    {
        $httpResponse = $this->getMockResponse('DirectPurchase3dSecure.txt');
        $response = Response::create($httpResponse->getBody(), $this->purchaseRequest);

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getGatewayReference());
        $this->assertNull($response->getMessage());
        $this->assertSame('https://test.sagepay.com/Simulator/3DAuthPage.asp', $response->getRedirectUrl());

        $formData = $response->getFormData();
        $this->assertSame('065379457749061954', $formData['MD']);
        $this->assertSame('BSkaFwYFFTYAGyFbAB0LFRYWBwsBZw0EGwECEX9YRGFWc08pJCVVKgAANS0KADoZCCAMBnIeOxcWRg0LERdOOTQRDFRdVHNYUgwTMBsBCxABJw4DJHE+ERgPCi8MVC0HIAROCAAfBUk4ER89DD0IWDkvMQ1VdFwoUFgwXVYvbHgvMkdBXXNbQGIjdl1ZUEc1XSwqAAgUUicYBDYcB3I2AjYjIzsn', $formData['PaReq']);
        $this->assertSame('https://www.example.com/return', $formData['TermUrl']);
    }

    public function testCaptureSuccess()
    {
        $httpResponse = $this->getMockResponse('CaptureSuccess.txt');
        $response = Response::create($httpResponse->getBody(), $this->captureRequest);

        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getGatewayReference());
        $this->assertSame('The transaction was RELEASEed successfully.', $response->getMessage());
    }

    public function testCaptureFailure()
    {
        $httpResponse = $this->getMockResponse('CaptureFailure.txt');
        $response = Response::create($httpResponse->getBody(), $this->captureRequest);

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getGatewayReference());
        $this->assertSame('You are trying to RELEASE a transaction that has already been RELEASEd or ABORTed.', $response->getMessage());
    }

    public function testServerPurchaseSuccess()
    {
        $httpResponse = $this->getMockResponse('ServerPurchaseSuccess.txt');
        $response = Response::create($httpResponse->getBody(), $this->purchaseRequest);

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getGatewayReference());
        $this->assertNull($response->getMessage());
        $this->assertSame('https://test.sagepay.com/Simulator/VSPServerPaymentPage.asp?TransactionID={1E7D9C70-DBE2-4726-88EA-D369810D801D}', $response->getRedirectUrl());
    }

    public function testServerPurchaseFailure()
    {
        $httpResponse = $this->getMockResponse('ServerPurchaseFailure.txt');
        $response = Response::create($httpResponse->getBody(), $this->purchaseRequest);

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getGatewayReference());
        $this->assertSame('The Description field should be between 1 and 100 characters long.', $response->getMessage());
    }
}
