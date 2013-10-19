<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PayPal\Message;

/**
 * PayPal Express Checkout Details Request
 */
class ExpressCheckoutDetailsRequest extends AbstractRequest
{
    public function getData()
    {
        $data = $this->getBaseData('GetExpressCheckoutDetails');
        $data['TOKEN'] = $this->httpRequest->query->get('token');

        return $data;
    }
}
