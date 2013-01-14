<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->request = new Request();
    }

    public function testConstruct()
    {
        $this->assertEmpty($this->request->amount);
        $this->assertEmpty($this->request->currency);
    }

    public function testAmountCastsToInteger()
    {
        $this->request->amount = '6.1';
        $this->assertEquals(6, $this->request->amount);
    }

    public function testAmountDollars()
    {
        $this->request->amount = 1366;
        $this->assertSame('13.66', $this->request->amountDollars);
    }

    public function testCurrency()
    {
        $this->request->currency = 'USD';
        $this->assertSame('USD', $this->request->currency);
    }

    public function testCurrencyLowercase()
    {
        $this->request->currency = 'usd';
        $this->assertSame('USD', $this->request->currency);
    }

    public function testCurrencyNotFound()
    {
        $this->request->currency = 'XYZ';
        $this->assertNull($this->request->currency);
        $this->assertNull($this->request->currencyNumeric);
        $this->assertSame(2, $this->request->currencyDecimals);
    }

    public function testCurrencyNumeric()
    {
        $this->request->currency = 'USD';
        $this->assertSame('840', $this->request->currencyNumeric);
    }

    public function testCurrencyDecimals()
    {
        $this->request->currency = 'JPY';
        $this->assertSame(0, $this->request->currencyDecimals);
    }
}
