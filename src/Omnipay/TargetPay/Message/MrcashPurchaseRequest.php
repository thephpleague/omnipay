<?php

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
            'amount' => $this->getAmountInteger(),
            'description' => $this->getDescription(),
            'lang' => $this->getLanguage(),
            'userip' => $this->getClientIp(),
            'returnurl' => $this->getReturnUrl(),
            'reporturl' => $this->getNotifyUrl(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getEndpoint()
    {
        return 'https://www.targetpay.com/mrcash/start';
    }
}
