<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\AuthorizeNet;

use Mockery as m;
use Tala\BaseGatewayTest;
use Tala\Request;

class SIMGatewayTest extends BaseGatewayTest
{
    public function setUp()
    {
        $this->httpClient = m::mock('\Tala\HttpClient\HttpClientInterface');
        $this->httpRequest = m::mock('\Symfony\Component\HttpFoundation\Request');

        $this->gateway = new SIMGateway($this->httpClient, $this->httpRequest);

        $this->options = array(
            'amount' => 1000,
            'returnUrl' => 'https://www.example.com/return',
        );
    }

    /**
     * @expectedException \Tala\Exception\InvalidRequestException
     * @expectedExceptionMessage The amount parameter is required
     */
    public function testAuthorizeRequiresAmount()
    {
        $this->options['amount'] = 0;
        $response = $this->gateway->authorize($this->options);
    }

    public function testAuthorize()
    {
        $response = $this->gateway->authorize($this->options);
        $this->assertInstanceOf('\Tala\FormRedirectResponse', $response);
        $this->assertNotEmpty($response->getRedirectUrl());

        $formData = $response->getFormData();
        $this->assertEquals('https://www.example.com/return', $formData['x_relay_url']);
    }
}
