<?php

namespace Omnipay\Pacnet\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Pacnet Response
 */
class Response extends AbstractResponse
{
    protected $body;

    public function __construct($request, $data)
    {
        $this->request = $request;
        $this->data = $data;
        parse_str($this->data->getBody(), $this->body);
    }

    public function isSuccessful()
    {

        if (isset($this->body['Status']) && ($this->body['Status'] == 'Approved' or $this->body['Status'] == 'Submitted' or $this->body['Status'] == 'InProgress' or $this->body['Status'] == 'Voided')) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    public function getMessage()
    {
        if (isset($this->body['Message'])) {
            return $this->body['Message'];
        }
    }

    public function getTransactionReference()
    {
        if (isset($this->body['TrackingNumber'])) {
            return $this->body['TrackingNumber'];
        }
    }

}
