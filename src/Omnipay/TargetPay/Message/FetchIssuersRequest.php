<?php

namespace Omnipay\TargetPay\Message;

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

class FetchIssuersRequest extends BaseAbstractRequest
{
    /**
     * @var string
     */
    protected $endpoint = 'https://www.targetpay.com/ideal/getissuers.php?format=xml';

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function sendData($data)
    {
        $httpResponse = $this->httpClient->get($this->endpoint)->send();

        return $this->response = new FetchIssuersResponse($this, $httpResponse->xml());
    }
}
