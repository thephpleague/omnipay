<?php

namespace Omnipay\SagePay\Message;

/**
 * Sage Pay Server Purchase Request
 */
class ServerPurchaseRequest extends ServerAuthorizeRequest
{
    protected $action = 'PAYMENT';
}
