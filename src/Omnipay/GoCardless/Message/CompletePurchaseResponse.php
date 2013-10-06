<?php

namespace Omnipay\GoCardless\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * GoCardless Complete Purchase Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    protected $transactionReference;

    public function __construct(RequestInterface $request, $data, $transactionReference)
    {
        parent::__construct($request, $data);
        $this->transactionReference = $transactionReference;
    }

    public function isSuccessful()
    {
        return !isset($this->data['error']);
    }

    public function getTransactionReference()
    {
        return $this->transactionReference;
    }

    public function getMessage()
    {
        if (!$this->isSuccessful()) {
            return reset($this->data['error']);
        }
    }
}
