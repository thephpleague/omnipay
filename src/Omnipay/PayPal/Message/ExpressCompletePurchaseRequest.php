<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal Express Complete Purchase Request
 */
class ExpressCompletePurchaseRequest extends ExpressCompleteAuthorizeRequest
{
    protected $action = 'Sale';
}
