<?php

namespace Omnipay\SecurePay\Message;

/**
 * SecurePay Direct Post Purchase Request
 */
class DirectPostPurchaseRequest extends DirectPostAuthorizeRequest
{
    public $txnType = '0';
}
