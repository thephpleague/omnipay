<?php

namespace Omnipay\AuthorizeNet\Message;

/**
 * Authorize.Net SIM Purchase Request
 */
class SIMPurchaseRequest extends SIMAuthorizeRequest
{
    protected $action = 'AUTH_CAPTURE';
}
