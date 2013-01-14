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

use Tala\AbstractParameterObject;

/**
 * Credit Card class
 */
class Request extends AbstractParameterObject
{
    private $currency;

    public function setAmount($value)
    {
        $this->parameters['amount'] = round($value);
    }

    public function getAmountDollars()
    {
        return number_format($this->amount / 100, $this->getCurrencyDecimals(), '.', '');
    }

    public function setCurrency($value)
    {
        $this->currency = Currency::find($value);
    }

    public function getCurrency()
    {
        return $this->currency ? $this->currency->getCode() : null;
    }

    public function getCurrencyNumeric()
    {
        return $this->currency ? $this->currency->getNumeric() : null;
    }

    public function getCurrencyDecimals()
    {
        return $this->currency ? $this->currency->getDecimals() : 2;
    }
}
