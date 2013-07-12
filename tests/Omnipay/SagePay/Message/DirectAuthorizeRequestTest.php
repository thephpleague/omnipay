<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Dave Amphlett <dave@davelopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\SagePay\Message;

use Omnipay\TestCase;

class DirectAuthorizeRequestTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->purchaseOptions = array(
            'amount' => '10.00',
            'transactionId' => '123',
            'card' => $this->getValidCard(),
            'returnUrl' => 'https://www.example.com/return',
        );

        $this->captureOptions = array(
            'amount' => '10.00',
            'transactionReference' => '{"SecurityKey":"JEUPDN1N7E","TxAuthNo":"4255","VPSTxId":"{F955C22E-F67B-4DA3-8EA3-6DAC68FA59D2}","VendorTxCode":"438791"}',
        );
    }

    public function testGetData()
    {
        $request = new DirectAuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());

        $parameters = array(
            'amount' => 321.98,
            'currency' => 'USD',
            'transactionId' => 'tx102030405',
            'description' => 'widgets',
            'returnUrl' => 'http://call.back.me/here/',
            'card' => array(
                'brand' => \Omnipay\Common\CreditCard::BRAND_MASTERCARD,
                'number' => '4111111111111111',
                'expiryMonth' => 3,
                'expiryYear' => (intval(gmdate('Y')) + 3),
                'firstName' => 'fname',
                'lastName' => 'lname',
                'billingAddress1' => 'baddr1',
                'billingAddress2' => 'baddr2',
                'billingCity' => 'bcity',
                'billingPostcode' => 'bpc',
                'billingState' => 'BS',
                'billingCountry' => 'GB',
                'billingPhone' => '+123456789',
                'shippingAddress1' => 'saddr1',
                'shippingAddress2' => 'saddr2',
                'shippingCity' => 'scity',
                'shippingPostcode' => 'spc',
                'shippingState' => 'SS',
                'shippingCountry' => 'GB',
                'shippingPhone' => '+44234567891',
                'email' => 'something@somewhere.com',
            ),
        );
        $request->initialize($parameters);
        $requestData = $request->getData();

        $this->assertSame('MC', $requestData['CardType']);
    }
    
}
