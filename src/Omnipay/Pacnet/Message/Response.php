<?php

namespace Omnipay\Pacnet\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Pacnet Response
 */
class Response extends AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        parse_str($data, $this->data);
    }

    public function isSuccessful()
    {
        return isset($this->data['Status']) && in_array($this->data['Status'], array(
            'Approved',
            'Submitted',
            'InProgress',
            'Voided'
        ));
    }

    public function getMessage()
    {
        return (isset($this->data['Message'])) ? $this->data['Message'] : null;
    }

    public function getTransactionReference()
    {
        return (isset($this->data['TrackingNumber'])) ? $this->data['TrackingNumber'] : null;
    }
}
