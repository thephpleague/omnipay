<?php

namespace Omnipay\Netaxept\Message;

use Omnipay\Common\CreditCard;
use Omnipay\TestCase;

class PurchaseRequestTest extends TestCase
{
    /**
     * @var \Omnipay\Netaxept\Message\PurchaseRequest
     */
    private $request;

    public function setUp()
    {
        $client = $this->getHttpClient();
        $request = $this->getHttpRequest();

        $this->request = new PurchaseRequest($client, $request);
    }

    public function testGetDataWithCard()
    {
        $this->request->setMerchantId('MERCH-123');
        $this->request->setPassword('PASSWORD-123');
        $this->request->setAmount('1.23');
        $this->request->setCurrency('USD');
        $this->request->setTransactionId('ABC-123');
        $this->request->setReturnUrl('http://return.domain.com/');

        $card = new CreditCard(array(
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'test@email.com',
            'phone' => '555-555-5555',
            'address1' => '123 NW Blvd',
            'address2' => 'Lynx Lane',
            'postcode' => '66605',
            'city' => 'Topeka',
            'country' => 'USA',
        ));
        $this->request->setCard($card);

        $expected = array(
            'merchantId' => 'MERCH-123',
            'token' => 'PASSWORD-123',
            'serviceType' => 'B',
            'orderNumber' => 'ABC-123',
            'currencyCode' => 'USD',
            'amount' => 123,
            'redirectUrl' => 'http://return.domain.com/',
            'customerFirstName' => 'John',
            'customerLastName' => 'Doe',
            'customerEmail' => 'test@email.com',
            'customerPhoneNumber' => '555-555-5555',
            'customerAddress1' => '123 NW Blvd',
            'customerAddress2' => 'Lynx Lane',
            'customerPostcode' => '66605',
            'customerTown' => 'Topeka',
            'customerCountry' => 'USA',
        );

        $this->assertEquals($expected, $this->request->getData());
    }
}
