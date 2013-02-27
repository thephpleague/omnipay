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
 * Authorize.Net AIM Purchase Request
 */
class AIMPurchaseRequest extends AIMAuthorizeRequest
{
    protected $action = 'AUTH_CAPTURE';

    public function getData()
    {
        $this->validate('amount', 'card');
        $this->getCard()->validate();

        $data = $this->getBaseData();
        $data['x_customer_ip'] = $this->getClientIp();
        $data['x_card_num'] = $this->getCard()->getNumber();
        $data['x_exp_date'] = $this->getCard()->getExpiryDate('my');
        $data['x_card_code'] = $this->getCard()->getCvv();

        if ($this->getTestMode()) {
            $data['x_test_request'] = 'TRUE';
        }

        return array_merge($data, $this->getBillingData());
    }
}
