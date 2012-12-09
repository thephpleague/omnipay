<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Core\Tests;

use Tala\Core\CreditCard;

class CreditCardTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->card = new CreditCard(array(
            'firstName' => 'Test',
            'lastName' => 'User',
            'number' => '4222222222222222',
            'expiryMonth' => 4,
            'expiryYear' => 2049,
            'cvv' => '123',
        ));
    }

    public function testFirstName()
    {
        $this->card->firstName = 'Bob';
        $this->assertEquals('Bob', $this->card->firstName);
    }

    public function testLastName()
    {
        $this->card->lastName = 'Smith';
        $this->assertEquals('Smith', $this->card->lastName);
    }

    public function testGetName()
    {
        $this->card->firstName = 'Bob';
        $this->card->lastName = 'Smith';
        $this->assertEquals('Bob Smith', $this->card->name);
    }

    public function testSetName()
    {
        $this->card->name = 'Bob Smith';
        $this->assertEquals('Bob', $this->card->firstName);
        $this->assertEquals('Smith', $this->card->lastName);
    }

    public function testSetNameWithOneName()
    {
        $this->card->name = 'Bob';
        $this->assertEquals('Bob', $this->card->firstName);
        $this->assertEquals('', $this->card->lastName);
    }

    public function testSetNameWithMultipleNames()
    {
        $this->card->name = 'Bob John Smith';
        $this->assertEquals('Bob', $this->card->firstName);
        $this->assertEquals('John Smith', $this->card->lastName);
    }

    public function testNumber()
    {
        $this->card->number = '4000000000000000';
        $this->assertEquals('4000000000000000', $this->card->number);
    }

    public function testSetNumberStripsNonDigits()
    {
        $this->card->number = '4000 0000 00b00 0000';
        $this->assertEquals('4000000000000000', $this->card->number);
    }

    public function testExpiryMonth()
    {
        $this->card->expiryMonth = 9;
        $this->assertSame(9, $this->card->expiryMonth);
    }

    public function testExpiryMonthLeadingZeros()
    {
        $this->card->expiryMonth = '09';
        $this->assertSame(9, $this->card->expiryMonth);
    }

    public function testExpiryYear()
    {
        $this->card->expiryYear = 2012;
        $this->assertSame(2012, $this->card->expiryYear);
    }

    public function testExpiryYearTwoDigits()
    {
        $this->card->expiryYear = '12';
        $this->assertSame(2012, $this->card->expiryYear);
    }

    public function testStartMonth()
    {
        $this->card->startMonth = 9;
        $this->assertSame(9, $this->card->startMonth);
    }

    public function testStartMonthLeadingZeros()
    {
        $this->card->startMonth = '09';
        $this->assertSame(9, $this->card->startMonth);
    }

    public function testStartYear()
    {
        $this->card->startYear = 2012;
        $this->assertSame(2012, $this->card->startYear);
    }

    public function testStartYearTwoDigits()
    {
        $this->card->startYear = '12';
        $this->assertSame(2012, $this->card->startYear);
    }

    public function testCvv()
    {
        $this->card->cvv = '456';
        $this->assertEquals('456', $this->card->cvv);
    }

    public function testType()
    {
        $this->card->type = 'visa';
        $this->assertEquals('visa', $this->card->type);
    }

    public function testBillingAddress1()
    {
        $this->card->billingAddress1 = '31 Spooner St';
        $this->assertEquals('31 Spooner St', $this->card->billingAddress1);
        $this->assertEquals('31 Spooner St', $this->card->address1);
    }

    public function testBillingAddress2()
    {
        $this->card->billingAddress2 = 'Suburb';
        $this->assertEquals('Suburb', $this->card->billingAddress2);
        $this->assertEquals('Suburb', $this->card->address2);
    }

    public function testBillingCity()
    {
        $this->card->billingCity = 'Quahog';
        $this->assertEquals('Quahog', $this->card->billingCity);
        $this->assertEquals('Quahog', $this->card->city);
    }

    public function testBillingPostcode()
    {
        $this->card->billingPostcode = '12345';
        $this->assertEquals('12345', $this->card->billingPostcode);
        $this->assertEquals('12345', $this->card->postcode);
    }

    public function testBillingState()
    {
        $this->card->billingState = 'RI';
        $this->assertEquals('RI', $this->card->billingState);
        $this->assertEquals('RI', $this->card->state);
    }

    public function testBillingCountry()
    {
        $this->card->billingCountry = 'US';
        $this->assertEquals('US', $this->card->billingCountry);
        $this->assertEquals('US', $this->card->country);
    }

    public function testShippingAddress1()
    {
        $this->card->shippingAddress1 = '31 Spooner St';
        $this->assertEquals('31 Spooner St', $this->card->shippingAddress1);
    }

    public function testShippingAddress2()
    {
        $this->card->shippingAddress2 = 'Suburb';
        $this->assertEquals('Suburb', $this->card->shippingAddress2);
    }

    public function testShippingCity()
    {
        $this->card->shippingCity = 'Quahog';
        $this->assertEquals('Quahog', $this->card->shippingCity);
    }

    public function testShippingPostcode()
    {
        $this->card->shippingPostcode = '12345';
        $this->assertEquals('12345', $this->card->shippingPostcode);
    }

    public function testShippingState()
    {
        $this->card->shippingState = 'RI';
        $this->assertEquals('RI', $this->card->shippingState);
    }

    public function testShippingCountry()
    {
        $this->card->shippingCountry = 'US';
        $this->assertEquals('US', $this->card->shippingCountry);
    }

    public function testAddress1()
    {
        $this->card->address1 = '31 Spooner St';
        $this->assertEquals('31 Spooner St', $this->card->address1);
        $this->assertEquals('31 Spooner St', $this->card->billingAddress1);
        $this->assertEquals('31 Spooner St', $this->card->shippingAddress1);
    }

    public function testAddress2()
    {
        $this->card->address2 = 'Suburb';
        $this->assertEquals('Suburb', $this->card->address2);
        $this->assertEquals('Suburb', $this->card->billingAddress2);
        $this->assertEquals('Suburb', $this->card->shippingAddress2);
    }

    public function testCity()
    {
        $this->card->city = 'Quahog';
        $this->assertEquals('Quahog', $this->card->city);
        $this->assertEquals('Quahog', $this->card->billingCity);
        $this->assertEquals('Quahog', $this->card->shippingCity);
    }

    public function testPostcode()
    {
        $this->card->postcode = '12345';
        $this->assertEquals('12345', $this->card->postcode);
        $this->assertEquals('12345', $this->card->billingPostcode);
        $this->assertEquals('12345', $this->card->shippingPostcode);
    }

    public function testState()
    {
        $this->card->state = 'RI';
        $this->assertEquals('RI', $this->card->state);
        $this->assertEquals('RI', $this->card->billingState);
        $this->assertEquals('RI', $this->card->shippingState);
    }

    public function testCountry()
    {
        $this->card->country = 'US';
        $this->assertEquals('US', $this->card->country);
        $this->assertEquals('US', $this->card->billingCountry);
        $this->assertEquals('US', $this->card->shippingCountry);
    }

    public function testPhone()
    {
        $this->card->phone = '12345';
        $this->assertEquals('12345', $this->card->phone);
    }

    public function testEmail()
    {
        $this->card->email = 'adrian@example.com';
        $this->assertEquals('adrian@example.com', $this->card->email);
    }
}
