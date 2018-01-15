<?php

namespace Omnipay\Tpay\Message;

/**
 * Tpay Deregister Request
 */
class DeregisterRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('cli_auth');

        $data['method'] = 'deregister';
        $data['cli_auth'] = $this->getToken();
        $data['language'] = $this->getLanguage();
        $data['sign'] = $this->getSign($data);
        $data['api_password'] = $this->getApiPassword();

        return $data;

    }

}
