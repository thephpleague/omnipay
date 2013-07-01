<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Buckaroo\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Buckaroo Complete Purchase Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    const CC_SUCCESS = '100';
    const PAYPAL_SUCCESS = '121';
    const IDEAL_SUCCESS = '801';

    public function isSuccessful()
    {
        $success_codes = array(
            static::CC_SUCCESS,
            static::PAYPAL_SUCCESS,
            static::IDEAL_SUCCESS,
        );

        return in_array($this->getCode(), $success_codes);
    }

    public function getTransactionReference()
    {
        if (isset($this->data['bpe_trx'])) {
            return $this->data['bpe_trx'];
        }
    }

    public function getCode()
    {
        if (isset($this->data['bpe_result'])) {
            return $this->data['bpe_result'];
        }
    }
}
