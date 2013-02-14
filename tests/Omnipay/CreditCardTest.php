<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay;

class CreditCardTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->card = new CreditCard;
        $this->card->setNumber('4111111111111111');
        $this->card->setFirstName('Example');
        $this->card->setLastName('Customer');
        $this->card->setExpiryMonth('4');
        $this->card->setExpiryYear(gmdate('Y')+2);
        $this->card->setCvv('123');
    }

    public function testConstructWithParams()
    {
        $card = new CreditCard(array('name' => 'Test Customer'));
        $this->assertSame('Test Customer', $card->getName());
    }

    public function testInitializeWithParams()
    {
        $card = new CreditCard;
        $card->initialize(array('name' => 'Test Customer'));
        $this->assertSame('Test Customer', $card->getName());
    }

    public function testToArrayKeys()
    {
        $card = new CreditCard;
        $output = $card->toArray();
        $this->assertArrayHasKey('firstName', $output);
        $this->assertArrayHasKey('lastName', $output);
        $this->assertArrayHasKey('number', $output);
        $this->assertArrayHasKey('expiryMonth', $output);
        $this->assertArrayHasKey('expiryYear', $output);
        $this->assertArrayHasKey('cvv', $output);
        $this->assertArrayHasKey('issueNumber', $output);
        $this->assertArrayHasKey('type', $output);
        $this->assertArrayHasKey('company', $output);
        $this->assertArrayHasKey('email', $output);
    }

    public function testToArrayValues()
    {
        $card = new CreditCard(array(
            'name' => 'Example Customer',
            'number' => '1234',
            'expiryMonth' => 6,
            'expiryYear' => 2016,
        ));

        $output = $card->toArray();
        $this->assertSame('Example', $output['firstName']);
        $this->assertSame('Customer', $output['lastName']);
        $this->assertSame('1234', $output['number']);
        $this->assertSame(6, $output['expiryMonth']);
        $this->assertSame(2016, $output['expiryYear']);
    }

    public function testValidateFixture()
    {
        $this->card->validate();
    }

    /**
     * @expectedException \Omnipay\Exception\InvalidCreditCardException
     * @expectedExceptionMessage The number parameter is required
     */
    public function testValidateNumberRequired()
    {
        $this->card->setNumber(null);
        $this->card->validate();
    }

    /**
     * @expectedException \Omnipay\Exception\InvalidCreditCardException
     * @expectedExceptionMessage The firstName parameter is required
     */
    public function testValidateFirstNameRequired()
    {
        $this->card->setFirstName(null);
        $this->card->validate();
    }

    /**
     * @expectedException \Omnipay\Exception\InvalidCreditCardException
     * @expectedExceptionMessage The lastName parameter is required
     */
    public function testValidateLastNameRequired()
    {
        $this->card->setLastName(null);
        $this->card->validate();
    }

    /**
     * @expectedException \Omnipay\Exception\InvalidCreditCardException
     * @expectedExceptionMessage The expiryMonth parameter is required
     */
    public function testValidateExpiryMonthRequired()
    {
        $this->card->setExpiryMonth(null);
        $this->card->validate();
    }

    /**
     * @expectedException \Omnipay\Exception\InvalidCreditCardException
     * @expectedExceptionMessage The expiryYear parameter is required
     */
    public function testValidateExpiryYearRequired()
    {
        $this->card->setExpiryYear(null);
        $this->card->validate();
    }

    /**
     * @expectedException \Omnipay\Exception\InvalidCreditCardException
     * @expectedExceptionMessage The cvv parameter is required
     */
    public function testValidateCvvRequired()
    {
        $this->card->setCvv(null);
        $this->card->validate();
    }

    /**
     * @expectedException \Omnipay\Exception\InvalidCreditCardException
     * @expectedExceptionMessage Card has expired
     */
    public function testValidateExpiryDate()
    {
        $this->card->setExpiryYear(gmdate('Y')-1);
        $this->card->validate();
    }

    /**
     * @expectedException \Omnipay\Exception\InvalidCreditCardException
     * @expectedExceptionMessage Card number is invalid
     */
    public function testValidateNumber()
    {
        $this->card->setNumber('4111111111111110');
        $this->card->validate();
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

    public function testStartMonth()
    {
        $this->card->setStartMonth(9);
        $this->assertSame(9, $this->card->getStartMonth());
    }

    public function testStartMonthLeadingZeros()
    {
        $this->card->setStartMonth('09');
        $this->assertSame(9, $this->card->getStartMonth());
    }

    public function testStartYear()
    {
        $this->card->setStartYear(2012);
        $this->assertSame(2012, $this->card->getStartYear());
    }

    public function testStartYearTwoDigits()
    {
        $this->card->setStartYear('12');
        $this->assertSame(2012, $this->card->getStartYear());
    }

    public function testCvv()
    {
        $this->card->setCvv('456');
        $this->assertEquals('456', $this->card->getCvv());
    }

    public function testIssueNumber()
    {
        $this->card->setIssueNumber('12');
        $this->assertSame('12', $this->card->getIssueNumber());
    }

    public function testType()
    {
        $this->card->setType('visa');
        $this->assertEquals('visa', $this->card->getType());
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

    public function testBillingPhone()
    {
        $this->card->setBillingPhone('12345');
        $this->assertSame('12345', $this->card->getBillingPhone());
        $this->assertSame('12345', $this->card->getPhone());
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

    public function testShippingPhone()
    {
        $this->card->setShippingPhone('12345');
        $this->assertEquals('12345', $this->card->getShippingPhone());
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

    public function testPhone()
    {
        $this->card->setPhone('12345');
        $this->assertEquals('12345', $this->card->getPhone());
        $this->assertEquals('12345', $this->card->getBillingPhone());
        $this->assertEquals('12345', $this->card->getShippingPhone());
    }

    public function testEmail()
    {
        $this->card->setEmail('adrian@example.com');
        $this->assertEquals('adrian@example.com', $this->card->getEmail());
    }
}
