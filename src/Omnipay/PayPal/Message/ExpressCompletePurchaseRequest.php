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
 * PayPal Express Complete Purchase Request
 */
class ExpressCompletePurchaseRequest extends ExpressCompleteAuthorizeRequest
{
    protected $action = 'Sale';
}
