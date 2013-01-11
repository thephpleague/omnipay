<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\AuthorizeNet;

use Tala\CreditCard;
use Tala\Request;

class SIMGatewayTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->gateway = new SIMGateway;

        $this->card = new CreditCard(array(
            'firstName' => 'Example',
            'lastName' => 'User',
        ));

        $this->request = new Request();
        $this->request->amount = 1000;
        $this->request->returnUrl = 'https://www.example.com/checkout/complete';
    }

    protected function getMockBrowser()
    {
        return $this->getMock('\Buzz\Browser');
    }

    protected function getMockResponse($message)
    {
        $response = $this->getMock('\Buzz\Message\Response');
        $response->expects($this->atLeastOnce())
            ->method('getContent')
            ->will($this->returnValue($message));

        return $response;
    }

    public function testAuthorizeRequiresAmount()
    {
        $this->setExpectedException('\Tala\Exception\MissingParameterException', 'The amount parameter is required');

        $this->request->amount = 0;
        $response = $this->gateway->authorize($this->request, $this->card);
    }

    public function testAuthorize()
    {
        $response = $this->gateway->authorize($this->request, $this->card);
        $this->assertInstanceOf('\Tala\FormRedirectResponse', $response);
        $this->assertNotEmpty($response->getRedirectUrl());

        $formData = $response->getFormData();
        $this->assertEquals('https://www.example.com/checkout/complete', $formData['x_relay_url']);
    }
}
