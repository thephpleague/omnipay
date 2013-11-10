<?php

namespace Omnipay\AuthorizeNet\Message;

/**
 * Authorize.Net SIM Authorize Request
 */
class SIMAuthorizeRequest extends AbstractRequest
{
    protected $action = 'AUTH_ONLY';

    public function getData()
    {
        $this->validate('amount', 'returnUrl');

        $data = array();
        $data['x_login'] = $this->getApiLoginId();
        $data['x_type'] = $this->action;
        $data['x_fp_sequence'] = mt_rand();
        $data['x_fp_timestamp'] = time();
        $data['x_delim_data'] = 'FALSE';
        $data['x_show_form'] = 'PAYMENT_FORM';
        $data['x_relay_response'] = 'TRUE';
        $data['x_relay_url'] = $this->getReturnUrl();
        $data['x_cancel_url'] = $this->getCancelUrl();

        if ($this->getTestMode()) {
            $data['x_test_request'] = 'TRUE';
        }

        $data = array_merge($data, $this->getBillingData());
        $data['x_fp_hash'] = $this->getHash($data);

        return $data;
    }

    public function getHash($data)
    {
        $fingerprint = implode(
            '^',
            array(
                $this->getApiLoginId(),
                $data['x_fp_sequence'],
                $data['x_fp_timestamp'],
                $data['x_amount']
            )
        ).'^';

        return hash_hmac('md5', $fingerprint, $this->getTransactionKey());
    }

    public function sendData($data)
    {
        return $this->response = new SIMAuthorizeResponse($this, $data, $this->getEndpoint());
    }
}
