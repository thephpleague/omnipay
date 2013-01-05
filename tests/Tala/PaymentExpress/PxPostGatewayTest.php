<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\PaymentExpress;

use Tala\CreditCard;
use Tala\Request;

class PxPostGatewayTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->gateway = new PxPostGateway(array(
            'username' => getenv('PAYMENTEXPRESS_USERNAME'),
            'password' => getenv('PAYMENTEXPRESS_PASSWORD'),
        ));

        $this->card = new CreditCard(array(
            'firstName' => 'Example',
            'lastName' => 'User',
            'number' => '4111111111111111',
            'expiryMonth' => '12',
            'expiryYear' => '2016',
            'cvv' => '123',
        ));

        $this->request = new Request();
        $this->request->amount = 1000;
    }

    /**
     * @group remote
     */
    public function testAuthorizeCaptureRemote()
    {
        $authRequest = new Request();
        $authRequest->amount = 1100;
        $authResponse = $this->gateway->authorize($authRequest, $this->card);

        $this->assertInstanceOf('\Tala\ResponseInterface', $authResponse);
        $this->assertNotEmpty($authResponse->getGatewayReference());

        $captureRequest = new Request();
        $captureRequest->gatewayReference = $authResponse->getGatewayReference();
        $captureRequest->amount = 1100;
        $captureResponse = $this->gateway->capture($captureRequest);

        $this->assertInstanceOf('\Tala\ResponseInterface', $captureResponse);
        $this->assertNotEmpty($captureResponse->getGatewayReference());
    }

    /**
     * @group remote
     */
    public function testPurchaseRefundRemote()
    {
        $purchaseRequest = new Request();
        $purchaseRequest->amount = 1300;
        $purchaseResponse = $this->gateway->purchase($purchaseRequest, $this->card);

        $this->assertInstanceOf('\Tala\ResponseInterface', $purchaseResponse);
        $this->assertNotEmpty($purchaseResponse->getGatewayReference());
    }
}
