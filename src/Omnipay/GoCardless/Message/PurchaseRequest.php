<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\GoCardless\Message;

/**
 * GoCardless Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $nonce;

    public function getNonce()
    {
        return $this->nonce;
    }

    public function setNonce($value)
    {
        $this->nonce = $value;

        return $this;
    }

    public function getData()
    {
        $this->validate(array('amount', 'returnUrl'));

        $data = array();
        $data['client_id'] = $this->appId;
        $data['nonce'] = $this->generateNonce();
        $data['timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
        $data['redirect_uri'] = $this->getReturnUrl();
        $data['cancel_uri'] = $this->getCancelUrl();
        $data['bill'] = array();
        $data['bill']['merchant_id'] = $this->merchantId;
        $data['bill']['amount'] = $this->getAmountDecimal();
        $data['bill']['name'] = $this->getDescription();

        if ($this->card) {
            $data['bill']['user'] = array();
            $data['bill']['user']['first_name'] = $this->card->getFirstName();
            $data['bill']['user']['last_name'] = $this->card->getLastName();
            $data['bill']['user']['email'] = $this->card->getEmail();
            $data['bill']['user']['billing_address1'] = $this->card->getAddress1();
            $data['bill']['user']['billing_address2'] = $this->card->getAddress2();
            $data['bill']['user']['billing_town'] = $this->card->getCity();
            $data['bill']['user']['billing_county'] = $this->card->getCountry();
            $data['bill']['user']['billing_postcode'] = $this->card->getPostcode();
        }

        $data['signature'] = $this->generateSignature($data);

        return $data;
    }

    public function send()
    {
        return $this->response = new PurchaseResponse($this, $this->getData());
    }

    /**
     * Generate a nonce for each request
     */
    protected function generateNonce()
    {
        $nonce = '';
        for ($i = 0; $i < 64; $i++) {
            // append random ASCII character
            $nonce .= chr(mt_rand(33, 126));
        }

        return base64_encode($nonce);
    }
}
