<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\PayPal;

use Tala\CreditCard;
use Tala\Request;

class ExpressGatewayTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->gateway = new ExpressGateway(array(
            'username' => getenv('PAYPAL_USERNAME'),
            'password' => getenv('PAYPAL_PASSWORD'),
            'signature' => getenv('PAYPAL_SIGNATURE'),
            'testMode' => (bool) getenv('PAYPAL_TEST_MODE'),
        ));

        $this->card = new CreditCard(array(
            'firstName' => 'Example',
            'lastName' => 'User',
            'number' => getenv('PAYPAL_CARD_NUMBER'),
            'expiryMonth' => getenv('PAYPAL_CARD_EXP_MONTH'),
            'expiryYear' => getenv('PAYPAL_CARD_EXP_YEAR'),
            'cvv' => getenv('PAYPAL_CARD_CVV'),
        ));
    }

    /**
     * @group remote
     */
    public function testAuthorizeRemote()
    {
        $request = new Request();
        $request->amount = 1000;
        $request->cancelUrl = 'https://www.example.com/checkout';
        $request->returnUrl = 'https://www.example.com/complete';
        $response = $this->gateway->authorize($request, $this->card);

        $this->assertInstanceOf('\Tala\RedirectResponse', $response);
        $this->assertNotEmpty($response->getRedirectUrl());
    }
}
