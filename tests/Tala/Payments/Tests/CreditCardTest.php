<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian.macneil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Payments\Tests;

use Tala\Payments\CreditCard;

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
        $this->card->setFirstName('Bob');
        $this->assertEquals('Bob', $this->card->getFirstName());
    }

    public function testLastName()
    {
        $this->card->setLastName('Smith');
        $this->assertEquals('Smith', $this->card->getLastName());
    }

    public function testGetName()
    {
        $this->card->setFirstName('Bob');
        $this->card->setLastName('Smith');
        $this->assertEquals('Bob Smith', $this->card->getName());
    }

    public function testSetName()
    {
        $this->card->setName('Bob Smith');
        $this->assertEquals('Bob', $this->card->getFirstName());
        $this->assertEquals('Smith', $this->card->getLastName());
    }

    public function testSetNameWithOneName()
    {
        $this->card->setName('Bob');
        $this->assertEquals('Bob', $this->card->getFirstName());
        $this->assertEquals('', $this->card->getLastName());
    }

    public function testSetNameWithMultipleNames()
    {
        $this->card->setName('Bob John Smith');
        $this->assertEquals('Bob', $this->card->getFirstName());
        $this->assertEquals('John Smith', $this->card->getLastName());
    }

    public function testNumber()
    {
        $this->card->setNumber('4000000000000000');
        $this->assertEquals('4000000000000000', $this->card->getNumber());
    }

    public function testSetNumberStripsNonDigits()
    {
        $this->card->setNumber('4000 0000 00b00 0000');
        $this->assertEquals('4000000000000000', $this->card->getNumber());
    }

    public function testExpiryMonth()
    {
        $this->card->setExpiryMonth(9);
        $this->assertSame(9, $this->card->getExpiryMonth());
    }

    public function testExpiryMonthLeadingZeros()
    {
        $this->card->setExpiryMonth('09');
        $this->assertSame(9, $this->card->getExpiryMonth());
    }

    public function testExpiryYear()
    {
        $this->card->setExpiryYear(2012);
        $this->assertSame(2012, $this->card->getExpiryYear());
    }

    public function testExpiryYearTwoDigits()
    {
        $this->card->setExpiryYear('12');
        $this->assertSame(2012, $this->card->getExpiryYear());
    }

    public function testCvv()
    {
        $this->card->setCvv('456');
        $this->assertEquals('456', $this->card->getCvv());
    }

    public function testBillingAddress1()
    {
        $this->card->setBillingAddress1('31 Spooner St');
        $this->assertEquals('31 Spooner St', $this->card->getBillingAddress1());
        $this->assertEquals('31 Spooner St', $this->card->getAddress1());
    }

    public function testBillingAddress2()
    {
        $this->card->setBillingAddress2('Suburb');
        $this->assertEquals('Suburb', $this->card->getBillingAddress2());
        $this->assertEquals('Suburb', $this->card->getAddress2());
    }

    public function testBillingCity()
    {
        $this->card->setBillingCity('Quahog');
        $this->assertEquals('Quahog', $this->card->getBillingCity());
        $this->assertEquals('Quahog', $this->card->getCity());
    }

    public function testBillingPostcode()
    {
        $this->card->setBillingPostcode('12345');
        $this->assertEquals('12345', $this->card->getBillingPostcode());
        $this->assertEquals('12345', $this->card->getPostcode());
    }

    public function testBillingState()
    {
        $this->card->setBillingState('RI');
        $this->assertEquals('RI', $this->card->getBillingState());
        $this->assertEquals('RI', $this->card->getState());
    }

    public function testBillingCountry()
    {
        $this->card->setBillingCountry('US');
        $this->assertEquals('US', $this->card->getBillingCountry());
        $this->assertEquals('US', $this->card->getCountry());
    }

    public function testShippingAddress1()
    {
        $this->card->setShippingAddress1('31 Spooner St');
        $this->assertEquals('31 Spooner St', $this->card->getShippingAddress1());
    }

    public function testShippingAddress2()
    {
        $this->card->setShippingAddress2('Suburb');
        $this->assertEquals('Suburb', $this->card->getShippingAddress2());
    }

    public function testShippingCity()
    {
        $this->card->setShippingCity('Quahog');
        $this->assertEquals('Quahog', $this->card->getShippingCity());
    }

    public function testShippingPostcode()
    {
        $this->card->setShippingPostcode('12345');
        $this->assertEquals('12345', $this->card->getShippingPostcode());
    }

    public function testShippingState()
    {
        $this->card->setShippingState('RI');
        $this->assertEquals('RI', $this->card->getShippingState());
    }

    public function testShippingCountry()
    {
        $this->card->setShippingCountry('US');
        $this->assertEquals('US', $this->card->getShippingCountry());
    }

    public function testAddress1()
    {
        $this->card->setAddress1('31 Spooner St');
        $this->assertEquals('31 Spooner St', $this->card->getAddress1());
        $this->assertEquals('31 Spooner St', $this->card->getBillingAddress1());
        $this->assertEquals('31 Spooner St', $this->card->getShippingAddress1());
    }

    public function testAddress2()
    {
        $this->card->setAddress2('Suburb');
        $this->assertEquals('Suburb', $this->card->getAddress2());
        $this->assertEquals('Suburb', $this->card->getBillingAddress2());
        $this->assertEquals('Suburb', $this->card->getShippingAddress2());
    }

    public function testCity()
    {
        $this->card->setCity('Quahog');
        $this->assertEquals('Quahog', $this->card->getCity());
        $this->assertEquals('Quahog', $this->card->getBillingCity());
        $this->assertEquals('Quahog', $this->card->getShippingCity());
    }

    public function testPostcode()
    {
        $this->card->setPostcode('12345');
        $this->assertEquals('12345', $this->card->getPostcode());
        $this->assertEquals('12345', $this->card->getBillingPostcode());
        $this->assertEquals('12345', $this->card->getShippingPostcode());
    }

    public function testState()
    {
        $this->card->setState('RI');
        $this->assertEquals('RI', $this->card->getState());
        $this->assertEquals('RI', $this->card->getBillingState());
        $this->assertEquals('RI', $this->card->getShippingState());
    }

    public function testCountry()
    {
        $this->card->setCountry('US');
        $this->assertEquals('US', $this->card->getCountry());
        $this->assertEquals('US', $this->card->getBillingCountry());
        $this->assertEquals('US', $this->card->getShippingCountry());
    }
}
