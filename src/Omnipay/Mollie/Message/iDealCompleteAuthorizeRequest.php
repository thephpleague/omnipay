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
 * Mollie Express Authorize Request
 */
class iDealCompleteAuthorizeRequest extends AbstractRequest
{
    /**
     * @param $data
     *
     * @return iDealCompleteAuthorizeResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new iDealCompleteAuthorizeResponse($this, $data);
    }

    /**
     * Get the data array for this message. For Mollie's iDeal gateway, this is
     * an array.
     *
     * @return array
     */
    public function getData() {
        $data = array(
            'a' => 'check',
            'partnerid' => $this->getPartnerId(),
            'transaction_id' => $this->httpRequest->query->get('transaction_id'),
        );
        if ($this->getTestMode()) {
            $data['testmode'] = 'true';
        }
        return $data;
    }
}
