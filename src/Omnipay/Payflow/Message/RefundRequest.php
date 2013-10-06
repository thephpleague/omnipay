<?php

namespace Omnipay\Payflow\Message;

/**
 * Payflow Refund Request
 */
class RefundRequest extends CaptureRequest
{
    protected $action = 'C';
}
