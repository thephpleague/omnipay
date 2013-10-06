<?php

namespace Omnipay\AuthorizeNet\Message;

/**
 * Authorize.Net AIM Authorize Request
 */
class AIMAuthorizeRequest extends AbstractRequest
{
    protected $action = 'AUTH_ONLY';

    public function getData()
    {
        $this->validate('amount', 'card');
        $this->getCard()->validate();

        $data = $this->getBaseData();
        $data['x_customer_ip'] = $this->getClientIp();
        $data['x_card_num'] = $this->getCard()->getNumber();
        $data['x_exp_date'] = $this->getCard()->getExpiryDate('my');
        $data['x_card_code'] = $this->getCard()->getCvv();
        $data['x_cust_id'] = $this->getCustomerId();

        if ($this->getTestMode()) {
            $data['x_test_request'] = 'TRUE';
        }

        return array_merge($data, $this->getBillingData());
    }
}
