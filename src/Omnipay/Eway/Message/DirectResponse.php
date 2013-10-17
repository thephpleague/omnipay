<?php

namespace Omnipay\Eway\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * eWAY Direct Response
 */
class DirectResponse extends AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;

        try {
            $this->data = new \SimpleXMLElement($data);
        } catch (\Exception $e) {
            throw new InvalidResponseException();
        }
    }

    public function isSuccessful()
    {
        return ($this->data->ewayTrxnStatus == "True");
    }

    public function isRedirect()
    {
        return false;
    }

    public function getTransactionReference()
    {
        return (int) $this->data->ewayTrxnNumber;
    }

    public function getMessage()
    {
        return (string) $this->data->ewayTrxnError;
    }
}
