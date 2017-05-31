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
 * Mollie iDeal Banklist Authorize Request
 */
class iDealBanklistRequest extends AbstractRequest
{
    /**
     *
     * @return array
     */
    public function getData()
    {
        $data = array(
            'a' => 'banklist',
        );

        if ($this->getTestMode()) {
            $data['testmode'] = 'true';
        }

        return $data;
    }

    protected function createResponse($httpResponse)
    {
        return $this->response = new iDealBanklistResponse($this, $httpResponse);
    }
}
