<?php

namespace Omnipay\SagePay\Message;

/**
 * Sage Pay Direct Purchase Request
 */
class DirectCreateTokenRequest extends DirectAuthorizeRequest
{
    protected $action = 'REMOVETOKEN';
}
