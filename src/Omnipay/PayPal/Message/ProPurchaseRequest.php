<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal Pro Purchase Request
 */
class ProPurchaseRequest extends ProAuthorizeRequest
{
    protected $action = 'Sale';
}
