<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\AuthorizeNet\Message;

use Omnipay\TestCase;

class CIMResponseTest extends TestCase
{
    public function testAuthorizeSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('CIMAuthorizeSuccess.txt');
        $response = new CIMResponse($this->getMockRequest(), $this->getXml($httpResponse));

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('2192438263', $response->getTransactionReference());
        $this->assertSame('This transaction has been approved.', $response->getMessage());
    }

    public function testAuthorizeFailure()
    {
        $httpResponse = $this->getMockHttpResponse('CIMAuthorizeFailure.txt');
        $response = new CIMResponse($this->getMockRequest(), $this->getXml($httpResponse));

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('0', $response->getTransactionReference());
        $this->assertSame('A duplicate transaction has been submitted.', $response->getMessage());
    }

    public function testCaptureSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('CIMCaptureSuccess.txt');
        $response = new CIMResponse($this->getMockRequest(), $this->getXml($httpResponse));

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('2192438263', $response->getTransactionReference());
        $this->assertSame('This transaction has been approved.', $response->getMessage());
    }

    public function testCaptureFailure()
    {
        $httpResponse = $this->getMockHttpResponse('CIMСaptureFailure.txt');
        $response = new CIMResponse($this->getMockRequest(), $this->getXml($httpResponse));

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('0', $response->getTransactionReference());
        $this->assertSame('A duplicate transaction has been submitted.', $response->getMessage());
    }

    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('CIMPurchaseSuccess.txt');
        $response = new CIMResponse($this->getMockRequest(), $this->getXml($httpResponse));

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('2192438263', $response->getTransactionReference());
        $this->assertSame('This transaction has been approved.', $response->getMessage());
    }

    public function testPurchaseFailure()
    {
        $httpResponse = $this->getMockHttpResponse('CIMPurchaseFailure.txt');
        $response = new CIMResponse($this->getMockRequest(), $this->getXml($httpResponse));

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('0', $response->getTransactionReference());
        $this->assertSame('A duplicate transaction has been submitted.', $response->getMessage());
    }

    public function testCreateProfileSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('CIMCreateProfileSuccess.txt');
        $response = new CIMResponse($this->getMockRequest(), $this->getXml($httpResponse));

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('17951308', $response->getCustomerProfileId());
        $this->assertSame('Successful.', $response->getMessage());
    }

    public function testCreateProfileFailure()
    {
        $httpResponse = $this->getMockHttpResponse('CIMCreateProfileFailure.txt');
        $response = new CIMResponse($this->getMockRequest(), $this->getXml($httpResponse));

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('E00039', $response->getCode());
        $this->assertSame('A duplicate record with ID 17951308 already exists.', $response->getMessage());
    }

    public function testUpdateProfileSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('CIMUpdateProfileSuccess.txt');
        $response = new CIMResponse($this->getMockRequest(), $this->getXml($httpResponse));

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('I00001', $response->getCode());
        $this->assertSame('Successful.', $response->getMessage());
    }

    public function testUpdateProfileFailure()
    {
        $httpResponse = $this->getMockHttpResponse('CIMUpdateProfileFailure.txt');
        $response = new CIMResponse($this->getMockRequest(), $this->getXml($httpResponse));

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('E00040', $response->getCode());
        $this->assertSame('The record cannot be found.', $response->getMessage());
    }

    public function testDeleteProfileSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('CIMDeleteProfileSuccess.txt');
        $response = new CIMResponse($this->getMockRequest(), $this->getXml($httpResponse));

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('I00001', $response->getCode());
        $this->assertSame('Successful.', $response->getMessage());
    }

    public function testDeleteProfileFailure()
    {
        $httpResponse = $this->getMockHttpResponse('CIMDeleteProfileFailure.txt');
        $response = new CIMResponse($this->getMockRequest(), $this->getXml($httpResponse));

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('E00040', $response->getCode());
        $this->assertSame('The record cannot be found.', $response->getMessage());
    }

    public function testCreateCardSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('CIMCreateCardSuccess.txt');
        $response = new CIMResponse($this->getMockRequest(), $this->getXml($httpResponse));

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('16829207', $response->getCustomerPaymentProfileId());
        $this->assertSame('This transaction has been approved.', $response->getMessage());
        $this->assertSame('000000', $response->getTransactionReference());
    }

    public function testCreateCardFailure()
    {
        $httpResponse = $this->getMockHttpResponse('CIMCreateCardFailure.txt');
        $response = new CIMResponse($this->getMockRequest(), $this->getXml($httpResponse));

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('E00027', $response->getCode());
        $this->assertSame('There is one or more missing or invalid required fields.', $response->getMessage());
    }

    public function testUpdateCardSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('CIMUpdateCardSuccess.txt');
        $response = new CIMResponse($this->getMockRequest(), $this->getXml($httpResponse));

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('This transaction has been approved.', $response->getMessage());
        $this->assertSame('000000', $response->getTransactionReference());
    }

    public function testUpdateCardFailure()
    {
        $httpResponse = $this->getMockHttpResponse('CIMUpdateCardFailure.txt');
        $response = new CIMResponse($this->getMockRequest(), $this->getXml($httpResponse));

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('E00027', $response->getCode());
        $this->assertSame('There is one or more missing or invalid required fields.', $response->getMessage());
    }

    /**
     * Parse the XML response body and return a SimpleXMLElement
     *
     * @return \SimpleXMLElement
     * @throws RuntimeException if the response body is not in XML format
     */
    public function getXml($httpResponse)
    {
        // cat not valid response xmlns which returned by Authorize.Net
        $body = str_replace('xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd"', '', $httpResponse->getBody(true));

        try {
            // Allow XML to be retrieved even if there is no response body
            $xml = new \SimpleXMLElement((string) $body ?: '<root />');
        } catch (\Exception $e) {
            throw new \RuntimeException('Unable to parse response body into XML: ' . $e->getMessage());
        }

        return $xml;
    }
}
