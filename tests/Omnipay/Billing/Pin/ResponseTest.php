<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Billing\Pin;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Omnipay\Exception\InvalidResponseException
     */
    public function testConstructEmpty()
    {
        $response = new Response("");
    }

    public function testConstructError()
    {
        $response = new Response('{"error":"standard_error_name","error_description":"A description of the error."}');

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('A description of the error.', $response->getMessage());
    }

    public function testConstructSuccess()
    {
        $response = new Response('{
            "response": {
                "token": "ch_lfUYEBK14zotCTykezJkfg",
                "success": true,
                "amount": 400,
                "currency": null,
                "description": "test charge",
                "email": "roland@pin.net.au",
                "ip_address": "203.192.1.172",
                "created_at": "2012-06-20T03:10:49Z",
                "status_message": "Success!",
                "error_message": null,
                "card": {
                    "token": "card_nytGw7koRg23EEp9NTmz9w",
                    "display_number": "XXXX-XXXX-XXXX-0000",
                    "scheme": "master",
                    "address_line1": "42 Sevenoaks St",
                    "address_line2": null,
                    "address_city": "Lathlain",
                    "address_postcode": "6454",
                    "address_state": "WA",
                    "address_country": "Australia"
                },
                "transfer": null
            }
        }');

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('ch_lfUYEBK14zotCTykezJkfg', $response->getGatewayReference());
        $this->assertEquals('Success!', $response->getMessage());
    }
}
