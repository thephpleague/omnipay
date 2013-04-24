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

class CIMGatewayTest extends GatewayTestCase
{
    protected $voidOptions;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new CIMGateway($this->getHttpClient(), $this->getHttpRequest());

        $this->purchaseOptions = array(
            'amount' => 1000,
            'customerProfileId' => '17911663',
            'customerPaymentProfileId' => '16809116'
        );

        $this->createProfileOptions = array(
            'customerEmail' => 'test@example.com'
        );

        $this->updateProfileOptions = array(
            'customerProfileId' => '17951308',
            'customerEmail' => 'omnipay@google.com'
        );

        $this->createCardOptions = array(
            'customerProfileId' => '17951308',
            'card' => array(
                'name' => 'Limosul Aliev',
                'number' => '4242424242424242',
                'expiryMonth' => '01',
                'expiryYear' => '2014',
                'cvv' => '133'
            )
        );

        $this->updateCardOptions = array(
            'customerProfileId' => '17951308',
            'customerPaymentProfileId' => '16829207',
            'card' => array(
                'name' => 'Limosul Aliev',
                'number' => '4111111111111111',
                'expiryMonth' => '07',
                'expiryYear' => '2017',
                'cvv' => '234'
            )
        );

        $this->deleteProfileOptions = array(
            'customerProfileId' => '17951308'
        );
    }

    public function testAuthorizeSuccess()
    {
        $this->setMockHttpResponse('CIMAuthorizeSuccess.txt');

        $response = $this->gateway->authorize($this->purchaseOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('2192438263', $response->getTransactionReference());
        $this->assertSame('This transaction has been approved.', $response->getMessage());
    }

    public function testAuthorizeFailure()
    {
        $this->setMockHttpResponse('CIMAuthorizeFailure.txt');

        $response = $this->gateway->authorize($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('0', $response->getTransactionReference());
        $this->assertSame('A duplicate transaction has been submitted.', $response->getMessage());
    }

    public function testCaptureSuccess()
    {
        $this->setMockHttpResponse('CIMCaptureSuccess.txt');

        $response = $this->gateway->capture($this->purchaseOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('2192438263', $response->getTransactionReference());
        $this->assertSame('This transaction has been approved.', $response->getMessage());
    }

    public function testCaptureFailure()
    {
        $this->setMockHttpResponse('CIMСaptureFailure.txt');

        $response = $this->gateway->capture($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('0', $response->getTransactionReference());
        $this->assertSame('A duplicate transaction has been submitted.', $response->getMessage());
    }

    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('CIMPurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->purchaseOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('2192438263', $response->getTransactionReference());
        $this->assertSame('This transaction has been approved.', $response->getMessage());
    }

    public function testPurchaseFailure()
    {
        $this->setMockHttpResponse('CIMPurchaseFailure.txt');

        $response = $this->gateway->purchase($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('0', $response->getTransactionReference());
        $this->assertSame('A duplicate transaction has been submitted.', $response->getMessage());
    }

    public function testVoidSuccess()
    {
        $this->setMockHttpResponse('CIMVoidSuccess.txt');

        $response = $this->gateway->void($this->purchaseOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('2192438263', $response->getTransactionReference());
        $this->assertSame('This transaction has been approved.', $response->getMessage());
    }

    public function testVoidFailure()
    {
        $this->setMockHttpResponse('CIMVoidFailure.txt');

        $response = $this->gateway->void($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('0', $response->getTransactionReference());
        $this->assertSame('A duplicate transaction has been submitted.', $response->getMessage());
    }

    public function testCreateProfileSuccess()
    {
        $this->setMockHttpResponse('CIMCreateProfileSuccess.txt');

        $response = $this->gateway->createProfile($this->createProfileOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('17951308', $response->getCustomerProfileId());
        $this->assertSame('Successful.', $response->getMessage());
    }

    public function testCreateProfileFailure()
    {
        $this->setMockHttpResponse('CIMCreateProfileFailure.txt');

        $response = $this->gateway->createProfile($this->createProfileOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('E00039', $response->getCode());
        $this->assertSame('A duplicate record with ID 17951308 already exists.', $response->getMessage());
    }

    public function testUpdateProfileSuccess()
    {
        $this->setMockHttpResponse('CIMUpdateProfileSuccess.txt');

        $response = $this->gateway->updateProfile($this->updateProfileOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('I00001', $response->getCode());
        $this->assertSame('Successful.', $response->getMessage());
    }

    public function testUpdateProfileFailure()
    {
        $this->setMockHttpResponse('CIMUpdateProfileFailure.txt');

        $response = $this->gateway->updateProfile($this->updateProfileOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('E00040', $response->getCode());
        $this->assertSame('The record cannot be found.', $response->getMessage());
    }

    public function testDeleteProfileSuccess()
    {
        $this->setMockHttpResponse('CIMDeleteProfileSuccess.txt');

        $response = $this->gateway->deleteProfile($this->deleteProfileOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('I00001', $response->getCode());
        $this->assertSame('Successful.', $response->getMessage());
    }

    public function testDeleteProfileFailure()
    {
        $this->setMockHttpResponse('CIMDeleteProfileFailure.txt');

        $response = $this->gateway->deleteProfile($this->deleteProfileOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('E00040', $response->getCode());
        $this->assertSame('The record cannot be found.', $response->getMessage());
    }

    public function testCreateCardSuccess()
    {
        $this->setMockHttpResponse('CIMCreateCardSuccess.txt');

        $response = $this->gateway->createCard($this->createCardOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('16829207', $response->getCustomerPaymentProfileId());
        $this->assertSame('This transaction has been approved.', $response->getMessage());
        $this->assertSame('000000', $response->getTransactionReference());
    }

    public function testCreateCardFailure()
    {
        $this->setMockHttpResponse('CIMCreateCardFailure.txt');

        $response = $this->gateway->createCard($this->createCardOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('E00027', $response->getCode());
        $this->assertSame('There is one or more missing or invalid required fields.', $response->getMessage());
    }

    public function testUpdateCardSuccess()
    {
        $this->setMockHttpResponse('CIMUpdateCardSuccess.txt');

        $response = $this->gateway->updateCard($this->updateCardOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('This transaction has been approved.', $response->getMessage());
        $this->assertSame('000000', $response->getTransactionReference());
    }

    public function testUpdateCardFailure()
    {
        $this->setMockHttpResponse('CIMUpdateCardFailure.txt');

        $response = $this->gateway->updateCard($this->updateCardOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('E00027', $response->getCode());
        $this->assertSame('There is one or more missing or invalid required fields.', $response->getMessage());
    }
}
