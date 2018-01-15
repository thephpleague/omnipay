<?php

namespace Omnipay\Tpay\Message;

/**
 * Tpay Capture Request
 */
class CaptureRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('cli_auth', 'sale_auth');

        $data['method'] = 'sale';
        $data['cli_auth'] = $this->getToken();
        $data['sale_auth'] = $this->getTransactionId();
        $data['sign'] = $this->getSign($data);
        $data['api_password'] = $this->getApiPassword();

        return $data;

    }

}
