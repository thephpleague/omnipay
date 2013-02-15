<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Stripe;

use Omnipay\TestCase;

class ResponseTest extends TestCase
{
    /**
     * @expectedException Omnipay\Common\Exception\InvalidResponseException
     */
    public function testConstructEmpty()
    {
        $response = new Response("");
    }

    public function testConstructError()
    {
        $response = new Response('{"error":{
            "code":"incorrect_number",
            "message": "Your card number is incorrect",
            "param": "number",
            "type": "card_error"}}');

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Your card number is incorrect', $response->getMessage());
    }

    public function testConstructSuccess()
    {
        $response = new Response('{
            "paid": false,
            "fee": 0,
            "card": {
                "address_line1_check": null,
                "type": "Visa",
                "address_country": null,
                "address_line1": null,
                "country": "US",
                "fingerprint": "CLZyv5pEFcb5bDLP",
                "address_line2": null,
                "object": "card",
                "address_city": null,
                "cvc_check": null,
                "address_zip": null,
                "name": "fdjsk fdjksl",
                "exp_month": 1,
                "address_zip_check": null,
                "address_state": null,
                "exp_year": 2013,
                "last4": "0002"
            },
            "invoice": null,
            "customer": null,
            "refunded": false,
            "fee_details": [{
                "type": "stripe_fee",
                "application": null,
                "currency": "usd",
                "amount": 0,
                "description": "Stripe processing fees"
            }],
            "object": "charge",
            "created": 1357253317,
            "failure_message": "Your card was declined",
            "currency": "usd",
            "amount_refunded": 0,
            "id": "ch_12RgN9L7XhO9mI",
            "amount": 1000,
            "livemode": false,
            "description": null,
            "dispute": null}');

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('ch_12RgN9L7XhO9mI', $response->getGatewayReference());
    }
}
