<?php

namespace Omnipay\Common;

use Omnipay\Tests\TestCase;

class AmountTest extends TestCase
{

    public function testConstruct()
    {
        $amount = new Amount('1000', 'USD');
        $this->assertSame('1000', $amount->getAmount());
    }

    public function testConstructInteger()
    {
        $amount = new Amount(1000, 'USD');
        $this->assertSame('1000', $amount->getAmount());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructFloat()
    {
        new Amount(10.00, 'USD');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructDecimalString()
    {
        new Amount('10.00', 'USD');
    }

    public function testFromDecimal()
    {
        $amount = Amount::fromDecimal('10.00', 'USD');
        $this->assertSame('1000', $amount->getAmount());
    }

    public function testFromDecimalRounded()
    {
        $amount = Amount::fromDecimal('10', 'USD');
        $this->assertSame('1000', $amount->getAmount());

        $amount = Amount::fromDecimal(10, 'USD');
        $this->assertSame('1000', $amount->getAmount());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDecimalInvalid()
    {
        Amount::fromDecimal('1,234.00', 'USD');
    }

    public function testCurrencyString()
    {
        $amount = new Amount(1000, 'EUR');
        $this->assertSame('EUR', $amount->getCurrency()->getCode());
    }

    public function testCurrencyObject()
    {
        $currency = Currency::find('EUR');
        $amount = new Amount(1000, $currency);

        $this->assertSame('EUR', $amount->getCurrency()->getCode());
    }

    public function testFormatted()
    {
        $amount = new Amount(1000, 'USD');

        $this->assertSame('10.00', $amount->getFormatted());
    }

    public function testGetAmountNoDecimals()
    {
        $amount = new Amount(1366, 'JPY');

        $this->assertSame('JPY', $amount->getCurrency()->getCode());
        $this->assertSame('1366', $amount->getAmount());
        $this->assertSame('1366', $amount->getFormatted());
    }

    public function testFromDecimalNoDecimals()
    {
        $amount = Amount::fromDecimal('10', 'JPY');
        $this->assertSame('10', $amount->getAmount());
    }

    public function testIsNegative()
    {
        $amount = new Amount(-1, 'USD');
        $this->assertTrue($amount->isNegative());

        $amount = new Amount(0, 'USD');
        $this->assertFalse($amount->isNegative());

        $amount = new Amount(1, 'USD');
        $this->assertFalse($amount->isNegative());
    }

    public function testIsZero()
    {
        $amount = new Amount(-1, 'USD');
        $this->assertFalse($amount->isZero());

        $amount = new Amount(0, 'USD');
        $this->assertTrue($amount->isZero());

        $amount = new Amount('0', 'USD');
        $this->assertTrue($amount->isZero());

        $amount = Amount::fromDecimal('0.00', 'USD');
        $this->assertTrue($amount->isZero());

        $amount = new Amount(1, 'USD');
        $this->assertFalse($amount->isZero());
    }

    public function testAmountNegativeDecimalString()
    {
        $amount = Amount::fromDecimal('-123.00', 'USD');

        $this->assertEquals('-12300', $amount->getAmount());
        $this->assertTrue($amount->isNegative());
    }

    public function testAmountNegativeDecimalFloat()
    {
        $amount = Amount::fromDecimal(-123.00, 'USD');

        $this->assertEquals('-12300', $amount->getAmount());
        $this->assertTrue($amount->isNegative());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAmountThousandsSepThrowsException()
    {
        Amount::fromDecimal('1,234', 'USD');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAmountInvalidFormatThrowsException()
    {
        new Amount('1.234', 'USD');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAmountInvalidTypeThrowsException()
    {
        new Amount(true, 'USD');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAmountNegativeStringThrowsException()
    {
        new Amount('-123.00', 'USD');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAmountNegativeFloatThrowsException()
    {
        new Amount(-123.00, 'USD');
    }
}
