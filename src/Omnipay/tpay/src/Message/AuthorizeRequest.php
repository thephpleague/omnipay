<?php

namespace Omnipay\Tpay\Message;

/**
 * Tpay Authorize Request
 */
class AuthorizeRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'cli_auth', 'description');

        $data['method'] = 'presale';
        $data['cli_auth'] = $this->getToken();
        $data['desc'] = $this->getDescription();
        $data['amount'] = $this->getAmount();
        if (!is_null($this->getCurrencyNumeric())) {
            $data['currency'] = $this->getCurrencyNumeric();
        }
        if (!is_null($this->getOrderId())) {
            $data['order_id'] = $this->getOrderId();
        }
        $data['language'] = $this->getLanguage();
        $data['sign'] = $this->getSign($data);
        $data['api_password'] = $this->getApiPassword();

        return $data;

    }

}
