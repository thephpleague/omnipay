<?php

namespace Omnipay\PaymentExpress\Message;

/**
 * PaymentExpress PxPost Refund Request
 */
class PxPostRefundRequest extends PxPostCaptureRequest
{
    protected $action = 'Refund';
}
