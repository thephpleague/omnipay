<?php

namespace Omnipay\Tpay\Message;

use Omnipay\Tpay\_class_tpay\Notifications\CardNotificationHandler;

/**
 * Tpay Complete Purchase Request
 */
class CompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'currency');
        $tpayHandler = new CardNotificationHandler($this->getApiKey(), $this->getApiPassword(),
            $this->getVerificationCode(), $this->getHashType());

        $localResources['amount'] = $this->getAmount();
        $tpayHandler->setAmount($this->getAmount());

        $localResources['currency'] = $this->getCurrencyNumeric();
        $tpayHandler->setCurrency($this->getCurrencyNumeric());

        if (!is_null($this->getOrderId())) {
            $localResources['order_id'] = $this->getOrderId();
            $tpayHandler->setOrderID($this->getOrderId());
        }

        $tpayResources = $tpayHandler->handleNotification();

        $tpayHandler->validateCardSign($tpayResources['sign'], $tpayResources['sale_auth'], $tpayResources['card'],
            $tpayResources['date'], 'correct', isset($tpayResources['test_mode']) ?
                $tpayResources['test_mode'] : '');

        return $tpayResources;

    }
}
