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

class ServerGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new ServerGateway($this->httpClient, $this->httpRequest);
        $this->gateway->setVendor('example');

        $this->purchaseOptions = array(
            'amount' => 1000,
            'transactionId' => '123',
            'card' => $this->getValidCard(),
            'returnUrl' => 'https://www.example.com/return',
        );

        $this->completePurchaseOptions = array(
            'amount' => 1000,
            'transactionId' => '123',
            'gatewayReference' => '{"SecurityKey":"JEUPDN1N7E","TxAuthNo":"4255","VPSTxId":"{F955C22E-F67B-4DA3-8EA3-6DAC68FA59D2}","VendorTxCode":"438791"}',
        );
    }

    public function testInheritsDirectGateway()
    {
        $this->assertInstanceOf('Omnipay\SagePay\DirectGateway', $this->gateway);
    }

    public function testAuthorizeSuccess()
    {
        $this->setMockResponse($this->httpClient, 'ServerPurchaseSuccess.txt');

        $response = $this->gateway->authorize($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getGatewayReference());
        $this->assertSame('Server transaction registered successfully.', $response->getMessage());
        $this->assertSame('https://test.sagepay.com/Simulator/VSPServerPaymentPage.asp?TransactionID={1E7D9C70-DBE2-4726-88EA-D369810D801D}', $response->getRedirectUrl());
    }

    public function testAuthorizeFailure()
    {
        $this->setMockResponse($this->httpClient, 'ServerPurchaseFailure.txt');

        $response = $this->gateway->authorize($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getGatewayReference());
        $this->assertSame('The Description field should be between 1 and 100 characters long.', $response->getMessage());
    }

    public function testCompleteAuthorizeSuccess()
    {
        $this->httpRequest->request->replace(
            array(
                'Status' => 'OK',
                'TxAuthNo' => 'b',
                'AVSCV2' => 'c',
                'AddressResult' => 'd',
                'PostCodeResult' => 'e',
                'CV2Result' => 'f',
                'GiftAid' => 'g',
                '3DSecureStatus' => 'h',
                'CAVV' => 'i',
                'AddressStatus' => 'j',
                'PayerStatus' => 'k',
                'CardType' => 'l',
                'Last4Digits' => 'm',
                'VPSSignature' => md5('{F955C22E-F67B-4DA3-8EA3-6DAC68FA59D2}438791OKbexamplecJEUPDN1N7Edefghijklm'),
            )
        );

        $response = $this->gateway->completeAuthorize($this->completePurchaseOptions)
            ->setHttpRequest($this->httpRequest)
            ->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('{"SecurityKey":"JEUPDN1N7E","TxAuthNo":"b","VPSTxId":"{F955C22E-F67B-4DA3-8EA3-6DAC68FA59D2}","VendorTxCode":"123"}', $response->getGatewayReference());
        $this->assertNull($response->getMessage());
    }

    /**
     * @expectedException Omnipay\Common\Exception\InvalidResponseException
     */
    public function testCompleteAuthorizeInvalid()
    {
        $response = $this->gateway->completeAuthorize($this->completePurchaseOptions)->send();
    }

    public function testPurchaseSuccess()
    {
        $this->setMockResponse($this->httpClient, 'ServerPurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getGatewayReference());
        $this->assertSame('Server transaction registered successfully.', $response->getMessage());
        $this->assertSame('https://test.sagepay.com/Simulator/VSPServerPaymentPage.asp?TransactionID={1E7D9C70-DBE2-4726-88EA-D369810D801D}', $response->getRedirectUrl());
    }

    public function testPurchaseFailure()
    {
        $this->setMockResponse($this->httpClient, 'ServerPurchaseFailure.txt');

        $response = $this->gateway->purchase($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getGatewayReference());
        $this->assertSame('The Description field should be between 1 and 100 characters long.', $response->getMessage());
    }

    public function testCompletePurchaseSuccess()
    {
        $this->httpRequest->request->replace(
            array(
                'Status' => 'OK',
                'TxAuthNo' => 'b',
                'AVSCV2' => 'c',
                'AddressResult' => 'd',
                'PostCodeResult' => 'e',
                'CV2Result' => 'f',
                'GiftAid' => 'g',
                '3DSecureStatus' => 'h',
                'CAVV' => 'i',
                'AddressStatus' => 'j',
                'PayerStatus' => 'k',
                'CardType' => 'l',
                'Last4Digits' => 'm',
                'VPSSignature' => md5('{F955C22E-F67B-4DA3-8EA3-6DAC68FA59D2}438791OKbexamplecJEUPDN1N7Edefghijklm'),
            )
        );

        $response = $this->gateway->completePurchase($this->completePurchaseOptions)
            ->setHttpRequest($this->httpRequest)
            ->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('{"SecurityKey":"JEUPDN1N7E","TxAuthNo":"b","VPSTxId":"{F955C22E-F67B-4DA3-8EA3-6DAC68FA59D2}","VendorTxCode":"123"}', $response->getGatewayReference());
        $this->assertNull($response->getMessage());
    }

    /**
     * @expectedException Omnipay\Common\Exception\InvalidResponseException
     */
    public function testCompletePurchaseInvalid()
    {
        $response = $this->gateway->completePurchase($this->completePurchaseOptions)->send();
    }
}
