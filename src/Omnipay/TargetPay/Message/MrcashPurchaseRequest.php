<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\TargetPay\Message;

class MrcashPurchaseRequest extends PurchaseRequest
{
    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $this->validate('amount', 'description', 'clientIp', 'returnUrl');

        return array(
            'rtlo' => $this->getSubAccountId(),
            'description' => $this->getDescription(),
            'amount' => $this->getAmountInteger(),
            'lang' => $this->getLanguage(),
            'userip' => $this->getClientIp(),
            'returnurl' => $this->getReturnUrl(),
            'reporturl' => $this->getNotifyUrl(),
        );
    }
}
