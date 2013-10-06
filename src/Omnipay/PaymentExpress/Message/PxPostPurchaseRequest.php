<?php

namespace Omnipay\PaymentExpress\Message;

/**
 * PaymentExpress PxPost Purchase Request
 */
class PxPostPurchaseRequest extends PxPostAuthorizeRequest
{
    protected $action = 'Purchase';
}
