<?php

namespace Tala\Payments\Gateway;

use Tala\Payments\AbstractGateway;

/**
 * PayPal Express Gateway Class
 *
 * This is an example of a gateway implementation. In the final library, this will be in a separate package.
 */
class PayPalExpressGateway extends AbstractGateway
{
    const CHECKOUT_URL = 'https://www.paypal.com/webscr';

    public function purchase($amount, $source, $options)
    {

    }

    public function purchaseReturn($amount, $source, $options)
    {

    }
}
