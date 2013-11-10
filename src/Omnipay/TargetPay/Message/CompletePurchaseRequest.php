<?php

namespace Omnipay\TargetPay\Message;

abstract class CompletePurchaseRequest extends AbstractRequest
{
    public function getExchangeOnce()
    {
        return $this->getParameter('exchangeOnce');
    }

    public function setExchangeOnce($value)
    {
        return $this->setParameter('exchangeOnce', $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $this->validate('transactionId');

        return array(
            'rtlo' => $this->getSubAccountId(),
            'trxid' => $this->getTransactionId(),
            'once' => $this->getExchangeOnce(),
            'test' => $this->getTestMode(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function sendData($data)
    {
        $httpResponse = $this->httpClient->get(
            $this->getEndpoint().'?'.http_build_query($data)
        )->send();

        return $this->response = new CompletePurchaseResponse($this, $httpResponse->getBody(true));
    }
}
