<?php

namespace Omnipay\Tpay\Message;

use InvalidArgumentException;

/**
 * Tpay Refund Request
 */
class RefundRequest extends AbstractRequest
{
    public function getData()
    {
        if (is_null($this->getToken()) && is_null($this->getTransactionId())) {
            throw new InvalidArgumentException('No client token or transaction ID provided.');
        }
        if (is_null($this->getTransactionId()) && is_null($this->getAmount())) {
            throw new InvalidArgumentException('You must provide amount for transactions with token only.');
        }
        $this->validate('description');

        $data['method'] = 'refund';
        if (!is_null($this->getToken())) {
            $data['cli_auth'] = $this->getToken();
        }
        if (!is_null($this->getTransactionId())) {
            $data['sale_auth'] = $this->getTransactionId();
        }
        $data['desc'] = $this->getDescription();
        if (!is_null($this->getAmount())) {
            $data['amount'] = $this->getAmount();
        }
        if (!is_null($this->getCurrencyNumeric())) {
            $data['currency'] = $this->getCurrencyNumeric();
        }
        $data['language'] = $this->getLanguage();
        $data['sign'] = $this->getSign($data);
        $data['api_password'] = $this->getApiPassword();

        return $data;

    }

}
