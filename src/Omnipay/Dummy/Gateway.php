<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Dummy;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Request;

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
    public function getName()
    {
        return 'Dummy';
    }

    public function defineSettings()
    {
        return array();
    }

    public function authorize($options)
    {
        return $this->purchase($options);
    }

    public function purchase($options)
    {
        $request = new Request($options);
        $request->validate(array('amount'));
        $source = $request->getCard();
        $source->validate();

        return new Response(uniqid());
    }
}
