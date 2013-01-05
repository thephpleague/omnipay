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
    public function setAmount($value)
    {
        $this->parameters['amount'] = round($value);
    }

    public function getAmountDollars()
    {
        return sprintf('%0.2f', $this->amount / 100);
    }
}
