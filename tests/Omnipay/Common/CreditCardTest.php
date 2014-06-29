<?php

namespace Omnipay\Common;

use Omnipay\Tests\TestCase;

class CreditCardTest extends TestCase
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

    public function testGetParamters()
    {
        $card = new CreditCard(array(
            'name' => 'Example Customer',
            'number' => '1234',
            'expiryMonth' => 6,
            'expiryYear' => 2016,
        ));

        $parameters = $card->getParameters();
        $this->assertSame('Example', $parameters['billingFirstName']);
        $this->assertSame('Customer', $parameters['billingLastName']);
        $this->assertSame('1234', $parameters['number']);
        $this->assertSame(6, $parameters['expiryMonth']);
        $this->assertSame(2016, $parameters['expiryYear']);
    }

    public function testValidateFixture()
    {
        $this->card->validate();
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidCreditCardException
     * @expectedExceptionMessage The number parameter is required
     */
    public function testValidateNumberRequired()
    {
        $this->card->setNumber(null);
        $this->card->validate();
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidCreditCardException
     * @expectedExceptionMessage The expiryMonth parameter is required
     */
    public function testValidateExpiryMonthRequired()
    {
        $this->card->setExpiryMonth(null);
        $this->card->validate();
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidCreditCardException
     * @expectedExceptionMessage The expiryYear parameter is required
     */
    public function testValidateExpiryYearRequired()
    {
        $this->card->setExpiryYear(null);
        $this->card->validate();
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidCreditCardException
     * @expectedExceptionMessage Card has expired
     */
    public function testValidateExpiryDate()
    {
        $this->card->setExpiryYear(gmdate('Y')-1);
        $this->card->validate();
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidCreditCardException
     * @expectedExceptionMessage Card number is invalid
     */
    public function testValidateNumber()
    {
        $this->card->setNumber('4111111111111110');
        $this->card->validate();
    }

    public function testGetSupportedBrands()
    {
        $brands = $this->card->getSupportedBrands();
        $this->assertInternalType('array', $brands);
        $this->assertArrayHasKey(CreditCard::BRAND_VISA, $brands);
    }

    public function testTitle()
    {
        $this->card->setTitle('Mr.');
        $this->assertEquals('Mr.', $this->card->getTitle());
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

    public function testGetNumberLastFourNull()
    {
        $this->card->setNumber(null);
        $this->assertNull($this->card->getNumberLastFour());
    }

    public function testGetNumberLastFour()
    {
        $this->card->setNumber('4000000000001234');
        $this->assertSame('1234', $this->card->getNumberLastFour());
    }

    public function testGetNumberLastFourNonDigits()
    {
        $this->card->setNumber('4000 0000 0000 12x34');
        $this->assertSame('1234', $this->card->getNumberLastFour());
    }

    public function testGetNumberMasked()
    {
        $this->card->setNumber('4000000000001234');

        $this->assertSame('XXXXXXXXXXXX1234', $this->card->getNumberMasked());
    }

    public function testGetNumberMaskedNonDigits()
    {
        $this->card->setNumber('4000 0000 0000 12x34');

        $this->assertSame('XXXXXXXXXXXX1234', $this->card->getNumberMasked());
    }

    public function testGetBrandDefault()
    {
        $card = new CreditCard;
        $this->assertNull($card->getBrand());
    }

    public function testGetBrandVisa()
    {
        $card = new CreditCard(array('number' => '4242424242424242'));
        $this->assertSame(CreditCard::BRAND_VISA, $card->getBrand());
    }

    public function testGetBrandMasterCard()
    {
        $card = new CreditCard(array('number' => '5555555555554444'));
        $this->assertSame(CreditCard::BRAND_MASTERCARD, $card->getBrand());
    }

    public function testGetBrandAmex()
    {
        $card = new CreditCard(array('number' => '378282246310005'));
        $this->assertSame(CreditCard::BRAND_AMEX, $card->getBrand());
    }

    public function testGetBrandDiscover()
    {
        $card = new CreditCard(array('number' => '6011111111111117'));
        $this->assertSame(CreditCard::BRAND_DISCOVER, $card->getBrand());
    }

    public function testGetBrandDinersClub()
    {
        $card = new CreditCard(array('number' => '30569309025904'));
        $this->assertSame(CreditCard::BRAND_DINERS_CLUB, $card->getBrand());
    }

    public function testGetBrandJcb()
    {
        $card = new CreditCard(array('number' => '3530111333300000'));
        $this->assertSame(CreditCard::BRAND_JCB, $card->getBrand());
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

    public function testExpiryDate()
    {
        $this->assertSame($this->card, $this->card->setExpiryMonth('09'));
        $this->assertSame($this->card, $this->card->setExpiryYear('2012'));
        $this->assertSame('092012', $this->card->getExpiryDate('mY'));
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

    public function testStartDate()
    {
        $this->card->setStartMonth('11');
        $this->card->setStartYear('2012');
        $this->assertEquals('112012', $this->card->getStartDate('mY'));
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

    public function testBillingTitle()
    {
        $this->card->setBillingTitle('Mrs.');
        $this->assertEquals('Mrs.', $this->card->getBillingTitle());
        $this->assertEquals('Mrs.', $this->card->getTitle());
    }

    public function testBillingFirstName()
    {
        $this->card->setBillingFirstName('Bob');
        $this->assertEquals('Bob', $this->card->getBillingFirstName());
        $this->assertEquals('Bob', $this->card->getFirstName());
    }

    public function testBillingLastName()
    {
        $this->card->setBillingLastName('Smith');
        $this->assertEquals('Smith', $this->card->getBillingLastName());
        $this->assertEquals('Smith', $this->card->getLastName());
    }

    public function testBillingName()
    {
        $this->card->setBillingFirstName('Bob');
        $this->card->setBillingLastName('Smith');
        $this->assertEquals('Bob Smith', $this->card->getBillingName());

        $this->card->setBillingName('John Foo');
        $this->assertEquals('John', $this->card->getBillingFirstName());
        $this->assertEquals('Foo', $this->card->getBillingLastName());
    }

    public function testBillingCompany()
    {
        $this->card->setBillingCompany('SuperSoft');
        $this->assertEquals('SuperSoft', $this->card->getBillingCompany());
        $this->assertEquals('SuperSoft', $this->card->getCompany());
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

    public function testBillingFax()
    {
        $this->card->setBillingFax('54321');
        $this->assertSame('54321', $this->card->getBillingFax());
        $this->assertSame('54321', $this->card->getFax());
    }

    public function testShippingTitle()
    {
        $this->card->setShippingTitle('Dr.');
        $this->assertEquals('Dr.', $this->card->getShippingTitle());
    }

    public function testShippingFirstName()
    {
        $this->card->setShippingFirstName('James');
        $this->assertEquals('James', $this->card->getShippingFirstName());
    }

    public function testShippingLastName()
    {
        $this->card->setShippingLastName('Doctor');
        $this->assertEquals('Doctor', $this->card->getShippingLastName());
    }

    public function testShippingName()
    {
        $this->card->setShippingFirstName('Bob');
        $this->card->setShippingLastName('Smith');
        $this->assertEquals('Bob Smith', $this->card->getShippingName());

        $this->card->setShippingName('John Foo');
        $this->assertEquals('John', $this->card->getShippingFirstName());
        $this->assertEquals('Foo', $this->card->getShippingLastName());
    }

    public function testShippingCompany()
    {
        $this->card->setShippingCompany('SuperSoft');
        $this->assertEquals('SuperSoft', $this->card->getShippingCompany());
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

    public function testShippingFax()
    {
        $this->card->setShippingFax('54321');
        $this->assertEquals('54321', $this->card->getShippingFax());
    }

    public function testCompany()
    {
        $this->card->setCompany('FooBar');
        $this->assertEquals('FooBar', $this->card->getCompany());
        $this->assertEquals('FooBar', $this->card->getBillingCompany());
        $this->assertEquals('FooBar', $this->card->getShippingCompany());
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

    public function testFax()
    {
        $this->card->setFax('54321');
        $this->assertEquals('54321', $this->card->getFax());
        $this->assertEquals('54321', $this->card->getBillingFax());
        $this->assertEquals('54321', $this->card->getShippingFax());
    }

    public function testEmail()
    {
        $this->card->setEmail('adrian@example.com');
        $this->assertEquals('adrian@example.com', $this->card->getEmail());
    }

    public function testBirthday()
    {
        $this->card->setBirthday('01-02-2000');
        $this->assertEquals('2000-02-01', $this->card->getBirthday());
        $this->assertEquals('01/02/2000', $this->card->getBirthday('d/m/Y'));
    }

    public function testBirthdayEmpty()
    {
        $this->card->setBirthday('');
        $this->assertNull($this->card->getBirthday());
    }

    public function testGender()
    {
        $this->card->setGender('female');
        $this->assertEquals('female', $this->card->getGender());
    }
}
