<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\Dummy;

use Tala\AbstractGateway;
use Tala\Request;

/**
 * Dummy Gateway
 *
 * This gateway is useful for testing. It simply authorizes any payment made using a valid
 * credit card number and expiry.
 *
 * Any card number which passes the Luhn algorithm and ends in 0 is authorized
 * Any card number which passes the Luhn algorithm and ends in 1 is declined
 */
class Gateway extends AbstractGateway
{
    public function authorize(Request $request, $source)
    {
        return $this->purchase($request, $source);
    }

    public function purchase(Request $request, $source)
    {
        $request->validateRequired('amount');

        $source->validateRequired(array('number', 'firstName', 'lastName', 'expiryMonth', 'expiryYear', 'cvv'));
        $source->validateNumber();

        return new Response;
    }
}
