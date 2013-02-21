<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\AuthorizeNet\Message;

/**
 * Authorize.Net SIM Authorize Request
 */
class SIMAuthorizeRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate(array('amount', 'returnUrl'));

        $data = array();
        $data['x_login'] = $this->apiLoginId;
        $data['x_type'] = $this->method;
        $data['x_fp_sequence'] = mt_rand();
        $data['x_fp_timestamp'] = time();
        $data['x_delim_data'] = 'FALSE';
        $data['x_show_form'] = 'PAYMENT_FORM';
        $data['x_relay_response'] = 'TRUE';
        $data['x_relay_url'] = $this->getReturnUrl();
        $data['x_cancel_url'] = $this->getCancelUrl();

        if ($this->testMode) {
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
                $this->apiLoginId,
                $data['x_fp_sequence'],
                $data['x_fp_timestamp'],
                $data['x_amount']
            )
        ).'^';

        return hash_hmac('md5', $fingerprint, $this->transactionKey);
    }

    public function createResponse($data)
    {
        return new SIMAuthorizeResponse($data);
    }
}
