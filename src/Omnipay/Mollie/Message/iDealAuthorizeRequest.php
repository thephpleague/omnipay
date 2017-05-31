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
class iDealAuthorizeRequest extends AbstractRequest
{
    protected function createResponse($data)
    {
        return $this->response = new iDealAuthorizeResponse($this, $data);
    }

    /**
     * Get the data for this request. See documentation at
     * https://www.mollie.nl/beheer/betaaldiensten/documentatie/ideal/
     *
     * @return mixed
     */
    public function getData() {
        $data = array(
            'a' => 'fetch',
            'partnerid' => $this->getPartnerId(),
            'amount' => $this->getAmount(),
            'bank_id' => str_pad($this->httpRequest->request->get('bank_id'), 4, '0', STR_PAD_LEFT),
            'description' => $this->getDescription(),
            'reporturl' => $this->getReportUrl(),
            'returnurl' => $this->getReturnUrl(),
            'profile_key' => $this->getProfileKey(),
        );
        if ($this->getTestMode()) {
            $data['testmode'] = 'true';
        }
        return $data;
    }
}
