<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\AuthorizeNet;

use Omnipay\GatewayTestCase;

class AIMGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new AIMGateway($this->httpClient, $this->httpRequest);

        $this->purchaseOptions = array(
            'amount' => 1000,
            'card' => $this->getValidCard(),
        );

        $this->captureOptions = array(
            'amount' => 1000,
            'gatewayReference' => '12345',
        );
    }

    public function testAuthorizeSuccess()
    {
        $this->setMockResponse($this->httpClient, 'AIMAuthorizeSuccess.txt');

        $response = $this->gateway->authorize($this->purchaseOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('2184493132', $response->getGatewayReference());
        $this->assertSame('This transaction has been approved.', $response->getMessage());
    }

    public function testAuthorizeFailure()
    {
        $this->setMockResponse($this->httpClient, 'AIMAuthorizeFailure.txt');

        $response = $this->gateway->authorize($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('0', $response->getGatewayReference());
        $this->assertSame('A valid amount is required.', $response->getMessage());
    }

    public function testCaptureSuccess()
    {
        $this->setMockResponse($this->httpClient, 'AIMCaptureSuccess.txt');

        $response = $this->gateway->capture($this->captureOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('2184494531', $response->getGatewayReference());
        $this->assertSame('This transaction has been approved.', $response->getMessage());
    }

    public function testCaptureFailure()
    {
        $this->setMockResponse($this->httpClient, 'AIMCaptureFailure.txt');

        $response = $this->gateway->capture($this->captureOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('0', $response->getGatewayReference());
        $this->assertSame('The transaction cannot be found.', $response->getMessage());
    }

    public function testPurchaseSuccess()
    {
        $this->setMockResponse($this->httpClient, 'AIMPurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->purchaseOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('2184492509', $response->getGatewayReference());
        $this->assertSame('This transaction has been approved.', $response->getMessage());
    }

    public function testPurchaseFailure()
    {
        $this->setMockResponse($this->httpClient, 'AIMPurchaseFailure.txt');

        $response = $this->gateway->purchase($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('0', $response->getGatewayReference());
        $this->assertSame('A valid amount is required.', $response->getMessage());
    }
}
