<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Common;

use Omnipay\Common\Currency;
use Omnipay\TestCase;

class CurrencyTest extends TestCase
{
    public function testFind()
    {
        $currency = Currency::find('USD');

        $this->assertSame('USD', $currency->getCode());
        $this->assertSame('840', $currency->getNumeric());
        $this->assertSame(2, $currency->getDecimals());
    }

    public function testFindLowercase()
    {
        $currency = Currency::find('usd');

        $this->assertSame('USD', $currency->getCode());
        $this->assertSame('840', $currency->getNumeric());
        $this->assertSame(2, $currency->getDecimals());
    }

    public function testUnknownCurrencyReturnsNull()
    {
        $currency = Currency::find('XYZ');

        $this->assertNull($currency);
    }

    public function testAll()
    {
        $currencies = Currency::all();

        $this->assertTrue(isset($currencies['USD']));
        $this->assertFalse(isset($currencies['XYZ']));
    }
}
