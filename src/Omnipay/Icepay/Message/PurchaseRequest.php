<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Icepay\Message;

class PurchaseRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $this->validate(
            'merchantId',
            'secretCode',
            'transactionId',
            'amount',
            'country',
            'currency',
            'clientIp',
            'issuer',
            'language',
            'paymentMethod'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function send()
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function generateSignature()
    {
        $raw = '';

        return sha1($raw);
    }
}
