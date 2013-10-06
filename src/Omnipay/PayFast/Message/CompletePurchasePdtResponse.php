<?php

namespace Omnipay\PayFast\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * PayFast Complete Purchase PDT Response
 */
class CompletePurchasePdtResponse extends AbstractResponse
{
    protected $status;

    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data = array();

        // parse ridiculous response format
        $lines = explode('\n', $data);
        $this->status = $lines[0];

        foreach ($lines as $line) {
            $parts = explode('=', $line, 2);
            $this->data[$parts[0]] = isset($parts[1]) ? urldecode($parts[1]) : null;
        }
    }

    public function isSuccessful()
    {
        return 'SUCCESS' === $this->status;
    }

    public function getMessage()
    {
        return $this->isSuccessful() ? $this->data['payment_status'] : $this->status;
    }
}
