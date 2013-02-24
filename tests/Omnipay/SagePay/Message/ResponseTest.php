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

class ResponseTest extends TestCase
{
    public function setUp()
    {
        $this->request = new DirectAuthorizeRequest(array(
            'transactionId' => '123456',
            'returnUrl' => 'https://www.example.com/return',
        ));
    }

    public function testDirectPurchaseSuccess()
    {
        $httpResponse = $this->getMockResponse('DirectPurchaseSuccess.txt');
        $response = new Response($httpResponse->getBody());
        $response->setRequest($this->request);

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('{"SecurityKey":"OUWLNYQTVT","TxAuthNo":"9962","VPSTxId":"{5A1BC414-5409-48DD-9B8B-DCDF096CE0BE}","VendorTxCode":"123456"}', $response->getGatewayReference());
        $this->assertSame('Direct transaction from Simulator.', $response->getMessage());
    }

    public function testDirectPurchaseFailure()
    {
        $httpResponse = $this->getMockResponse('DirectPurchaseFailure.txt');
        $response = new Response($httpResponse->getBody());
        $response->setRequest($this->request);

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getGatewayReference());
        $this->assertSame('The VendorTxCode \'984297\' has been used before.  Each transaction you send should have a unique VendorTxCode.', $response->getMessage());
    }

    public function testDirectPurchase3dSecure()
    {
        $httpResponse = $this->getMockResponse('DirectPurchase3dSecure.txt');
        $response = new Response($httpResponse->getBody());
        $response->setRequest($this->request);

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getGatewayReference());
        $this->assertNull($response->getMessage());
        $this->assertSame('https://test.sagepay.com/Simulator/3DAuthPage.asp', $response->getRedirectUrl());

        $redirectData = $response->getRedirectData();
        $this->assertSame('065379457749061954', $redirectData['MD']);
        $this->assertSame('BSkaFwYFFTYAGyFbAB0LFRYWBwsBZw0EGwECEX9YRGFWc08pJCVVKgAANS0KADoZCCAMBnIeOxcWRg0LERdOOTQRDFRdVHNYUgwTMBsBCxABJw4DJHE+ERgPCi8MVC0HIAROCAAfBUk4ER89DD0IWDkvMQ1VdFwoUFgwXVYvbHgvMkdBXXNbQGIjdl1ZUEc1XSwqAAgUUicYBDYcB3I2AjYjIzsn', $redirectData['PaReq']);
        $this->assertSame('https://www.example.com/return', $redirectData['TermUrl']);
    }

    public function testCaptureSuccess()
    {
        $httpResponse = $this->getMockResponse('CaptureSuccess.txt');
        $response = new Response($httpResponse->getBody());
        $response->setRequest($this->request);

        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getGatewayReference());
        $this->assertSame('The transaction was RELEASEed successfully.', $response->getMessage());
    }

    public function testCaptureFailure()
    {
        $httpResponse = $this->getMockResponse('CaptureFailure.txt');
        $response = new Response($httpResponse->getBody());
        $response->setRequest($this->request);

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getGatewayReference());
        $this->assertSame('You are trying to RELEASE a transaction that has already been RELEASEd or ABORTed.', $response->getMessage());
    }
}
