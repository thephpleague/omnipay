<?php

namespace Omnipay\Stripe\Message;

/**
 * Stripe Authorize Request
 */
class AuthorizeRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'currency');

        $data = array();
        $data['amount'] = $this->getAmountInteger();
        $data['currency'] = strtolower($this->getCurrency());
        $data['description'] = $this->getDescription();
        $data['capture'] = 'false';

        if ($this->getCardReference()) {
            $data['customer'] = $this->getCardReference();
        } elseif ($this->getToken()) {
            $data['card'] = $this->getToken();
        } elseif ($this->getCard()) {
            $data['card'] = $this->getCardData();
        } else {
            // one of cardReference, token, or card is required
            $this->validate('card');
        }

        return $data;
    }

    public function getEndpoint()
    {
        return $this->endpoint.'/charges';
    }
}
