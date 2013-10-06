<?php

namespace Omnipay\PaymentExpress\Message;

/**
 * PaymentExpress PxPay Purchase Request
 */
class PxPayPurchaseRequest extends PxPayAuthorizeRequest
{
    protected $action = 'Purchase';
}
