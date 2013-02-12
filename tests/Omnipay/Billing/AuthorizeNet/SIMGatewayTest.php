<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Billing\AuthorizeNet;

use Mockery as m;
use Omnipay\BaseGatewayTest;
use Omnipay\Request;

class SIMGatewayTest extends BaseGatewayTest
{
    public function setUp()
    {
        $this->httpClient = m::mock('\Omnipay\HttpClient\HttpClientInterface');
        $this->httpRequest = m::mock('\Symfony\Component\HttpFoundation\Request');

        $this->gateway = new SIMGateway($this->httpClient, $this->httpRequest);

        $this->options = array(
            'amount' => 1000,
            'returnUrl' => 'https://www.example.com/return',
        );
    }

    /**
     * @expectedException \Omnipay\Exception\InvalidRequestException
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
        $this->assertInstanceOf('\Omnipay\FormRedirectResponse', $response);
        $this->assertNotEmpty($response->getRedirectUrl());

        $formData = $response->getFormData();
        $this->assertEquals('https://www.example.com/return', $formData['x_relay_url']);
    }
}
