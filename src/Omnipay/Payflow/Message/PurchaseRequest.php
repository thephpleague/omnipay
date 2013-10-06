<?php

namespace Omnipay\Payflow\Message;

/**
 * Payflow Purchase Request
 */
class PurchaseRequest extends AuthorizeRequest
{
    protected $action = 'S';
}
