<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\TwoCheckout\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * 2Checkout Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $accountNumber;
    protected $secretWord;
    protected $testMode;

    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    public function setAccountNumber($value)
    {
        $this->accountNumber = $value;

        return $this;
    }

    public function getSecretWord()
    {
        return $this->secretWord;
    }

    public function setSecretWord($value)
    {
        $this->secretWord = $value;

        return $this;
    }

    public function getTestMode()
    {
        return $this->testMode;
    }

    public function setTestMode($value)
    {
        $this->testMode = $value;

        return $this;
    }

    public function getData()
    {
        $this->validate(array('amount', 'returnUrl'));

        $data = array();
        $data['sid'] = $this->accountNumber;
        $data['cart_order_id'] = $this->getTransactionId();
        $data['total'] = $this->getAmountDecimal();
        $data['tco_currency'] = $this->getCurrency();
        $data['fixed'] = 'Y';
        $data['skip_landing'] = 1;
        $data['x_receipt_link_url'] = $this->getReturnUrl();

        if ($this->card) {
            $data['card_holder_name'] = $this->card->getName();
            $data['street_address'] = $this->card->getAddress1();
            $data['street_address2'] = $this->card->getAddress2();
            $data['city'] = $this->card->getCity();
            $data['state'] = $this->card->getState();
            $data['zip'] = $this->card->getPostcode();
            $data['country'] = $this->card->getCountry();
            $data['phone'] = $this->card->getPhone();
            $data['email'] = $this->card->getEmail();
        }

        if ($this->testMode) {
            $data['demo'] = 'Y';
        }

        return $data;
    }

    public function createResponse($data)
    {
        return new PurchaseResponse($data);
    }
}
