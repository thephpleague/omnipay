<?php

namespace Omnipay\SagePay\Message;

/**
 * Sage Pay Direct Purchase Request
 */
class DirectPurchaseRequest extends DirectAuthorizeRequest
{
    protected $action = 'PAYMENT';
}
