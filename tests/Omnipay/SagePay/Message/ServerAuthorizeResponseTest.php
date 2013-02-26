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

class ServerAuthorizeResponseTest extends TestCase
{
    public function testServerPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('ServerPurchaseSuccess.txt');
        $response = new ServerAuthorizeResponse($this->getMockRequest(), $httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('Server transaction registered successfully.', $response->getMessage());
        $this->assertSame('https://test.sagepay.com/Simulator/VSPServerPaymentPage.asp?TransactionID={1E7D9C70-DBE2-4726-88EA-D369810D801D}', $response->getRedirectUrl());
    }

    public function testServerPurchaseFailure()
    {
        $httpResponse = $this->getMockHttpResponse('ServerPurchaseFailure.txt');
        $response = new ServerAuthorizeResponse($this->getMockRequest(), $httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('The Description field should be between 1 and 100 characters long.', $response->getMessage());
    }
}
