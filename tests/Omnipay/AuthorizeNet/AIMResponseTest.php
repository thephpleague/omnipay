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

use Omnipay\TestCase;

class AIMResponseTest extends TestCase
{
    /**
     * @expectedException Omnipay\Common\Exception\InvalidResponseException
     */
    public function testConstructEmpty()
    {
        $response = new AIMResponse('');
    }

    public function testAuthorizeSuccess()
    {
        $httpResponse = $this->getMockResponse('AIMAuthorizeSuccess.txt');
        $response = new AIMResponse($httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('2184493132', $response->getGatewayReference());
        $this->assertSame('This transaction has been approved.', $response->getMessage());
    }

    public function testAuthorizeFailure()
    {
        $httpResponse = $this->getMockResponse('AIMAuthorizeFailure.txt');
        $response = new AIMResponse($httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('0', $response->getGatewayReference());
        $this->assertSame('A valid amount is required.', $response->getMessage());
    }

    public function testCaptureSuccess()
    {
        $httpResponse = $this->getMockResponse('AIMCaptureSuccess.txt');
        $response = new AIMResponse($httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('2184494531', $response->getGatewayReference());
        $this->assertSame('This transaction has been approved.', $response->getMessage());
    }

    public function testCaptureFailure()
    {
        $httpResponse = $this->getMockResponse('AIMCaptureFailure.txt');
        $response = new AIMResponse($httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('0', $response->getGatewayReference());
        $this->assertSame('The transaction cannot be found.', $response->getMessage());
    }

    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockResponse('AIMPurchaseSuccess.txt');
        $response = new AIMResponse($httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('2184492509', $response->getGatewayReference());
        $this->assertSame('This transaction has been approved.', $response->getMessage());
    }

    public function testPurchaseFailure()
    {
        $httpResponse = $this->getMockResponse('AIMPurchaseFailure.txt');
        $response = new AIMResponse($httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('0', $response->getGatewayReference());
        $this->assertSame('A valid amount is required.', $response->getMessage());
    }
}
