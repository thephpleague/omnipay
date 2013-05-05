<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Stripe\Message;

/**
 * Stripe Authorize Request
 */
class AuthorizeRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'currency');

        $data = array();
        $data['amount'] = $this->getAmount();
        $data['currency'] = strtolower($this->getCurrency());
        $data['description'] = $this->getDescription();
        $data['capture'] = 'false';

        if ($this->getCardReference()) {
            $data['customer'] = $this->getCardReference();
        } elseif ($this->getCardToken()) {
            $data['card'] = $this->getCardToken();
        } elseif ($this->getCard()) {
            $data['card'] = $this->getCardData();
        } else {
            // one of cardReference, cardToken, or card is required
            $this->validate('card');
        }

        return $data;
    }

    public function getEndpoint()
    {
        return $this->endpoint.'/charges';
    }
}
