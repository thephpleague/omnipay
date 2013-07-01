<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Mollie\Message;

/**
 * Mollie Complete Purchase Request
 */
class CompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('partnerId');

        $data = $this->getBaseData();
        $data['a'] = 'check';
        $data['partnerid'] = $this->getPartnerId();
        $data['transaction_id'] = $this->httpRequest->query->get('transaction_id');

        return $data;
    }
}
