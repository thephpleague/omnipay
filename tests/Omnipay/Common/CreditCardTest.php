<?php

namespace Omnipay\Common;

use Omnipay\Tests\TestCase;

class CreditCardTest extends TestCase
{
    public function setUp()
    {
        $this->card = new CreditCard;
        $this->card->setNumber('4111111111111111');
        $this->card->setExpiryMonth('4');
        $this->card->setExpiryYear(gmdate('Y')+2);
        $this->card->setCvv('123');
    }

    public function testConstructWithParams()
    {
        $card = new CreditCard(array('number' => '4111111111111111'));
        $this->assertSame('4111111111111111', $card->getNumber());
    }

    public function testInitializeWithParams()
    {
        $card = new CreditCard;
        $card->initialize(array('number' => '4111111111111111'));
        $this->assertSame('4111111111111111', $card->getNumber());
    }

    public function testGetParamters()
    {
        $card = new CreditCard(array(
            'number' => '1234',
            'expiryMonth' => 6,
            'expiryYear' => 2016,
        ));

        $parameters = $card->getParameters();
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

    public function testCustomSupportedBrand()
    {
        $this->card->addSupportedBrand('omniexpress', '/^9\d{12}(\d{3})?$/');
        $this->assertArrayHasKey('omniexpress', $this->card->getSupportedBrands());
    }

    public function testCustomBrandWorks()
    {
        $this->card->addSupportedBrand('omniexpress', '/^9\d{12}(\d{3})?$/');
        $this->assertArrayHasKey('omniexpress', $this->card->getSupportedBrands());
        $this->card->setNumber('9111111111111110');
        $this->card->validate();
        $this->assertEquals('omniexpress', $this->card->getBrand());
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

    /**
     * @expectedException Omnipay\Common\Exception\InvalidCreditCardException
     * @expectedExceptionMessage Card number is invalid
     */
    public function testInvalidLuhn()
    {
        $this->card->setNumber('43');
        $this->card->validate();
    }

    /**
     * @expectedException Omnipay\Common\Exception\InvalidCreditCardException
     * @expectedExceptionMessage Card number should have 12 to 19 digits
     */
    public function testInvalidShortCard()
    {
        $this->card->setNumber('4440');
        $this->card->validate();
    }
}
